<?php

namespace Tab\Bundle\FrontendBundle\Form\Extension;

use Oro\Bundle\CheckoutBundle\Entity\Checkout;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\ShoppingListBundle\Entity\ShoppingList;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowItem;
use Oro\Bundle\WorkflowBundle\Form\Type\WorkflowTransitionType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkflowTransitionTypeExtension extends AbstractTypeExtension
{

    protected const SQL = <<<SQL
select count(0) as count from oro_checkout_line_item where checkout_id = :checkoutId
union all
select count(0) as count from oro_shopping_list_line_item where shopping_list_id = :shoppingListId and order_id is null
SQL;


    /** @var DoctrineHelper */
    protected DoctrineHelper $doctrineHelper;

    /**
     * @param DoctrineHelper $doctrineHelper
     */
    public function __construct(DoctrineHelper $doctrineHelper)
    {
        $this->doctrineHelper = $doctrineHelper;
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined('allow_remove_source')
            ->setAllowedTypes('allow_remove_source', 'boolean')
            ->setDefault('allow_remove_source', function (Options $options) {
                $item = $options['workflow_item'] ?? null;
                return !$item instanceof WorkflowItem || $this->allowRemoveSource($item);
            })
        ;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['allow_remove_source'] || $builder->add('remove_source', HiddenType::class);
        $builder
            ->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'preSubmit']);
    }

    /**
     * @param FormEvent $event
     * @return void
     */
    public function preSubmit(FormEvent $event)
    {
        if (!$event->getForm()->getConfig()->getOption('allow_remove_source')) {
            $data = $event->getData();
            $data['remove_source'] = '';
            $event->setData($data);
        }
    }

    /**
     * @param WorkflowItem $item
     * @return bool
     */
    protected function allowRemoveSource(WorkflowItem $item): bool
    {
        // Allow by default.
        $result = true;
        $checkout = $item->getEntity();
        if ($checkout instanceof Checkout && $item->getWorkflowName() === 'tab_checkout') {
            $result = false;
            $shoppingList = $checkout->getSourceEntity();
            if ($shoppingList instanceof ShoppingList) {
                $connection = $this->doctrineHelper->getEntityManager(Checkout::class)->getConnection();
                $counts = $connection->executeQuery(self::SQL, [
                    'checkoutId' => $checkout->getId(),
                    'shoppingListId' => $shoppingList->getId(),
                ])->fetchFirstColumn();
                $result = $counts[0] === $counts[1];
            }
        }
        return $result;
    }

    public static function getExtendedTypes(): iterable
    {
        return [WorkflowTransitionType::class];
    }
}
