<?php

namespace Practice\Bundle\CheckoutBundle\Action;

use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Oro\Bundle\CheckoutBundle\Entity\Checkout;
use Oro\Bundle\OrderBundle\Entity\Order;
use Oro\Component\Action\Exception\InvalidParameterException;
use Oro\Component\Action\Exception\NotManageableEntityException;
use Oro\Component\ConfigExpression\ContextAccessor;
use Psr\Log\LoggerAwareTrait;
use Oro\Component\Action\Action\AbstractAction;


class ShoppingListLinkOrderAction extends AbstractAction
{
    use LoggerAwareTrait;

    public const NAME = 'shopping_list_link_order';

    /** @var Checkout */
    protected $checkout;

    /** @var Order */
    protected $order;

    /**
     * @var ManagerRegistry
     */
    protected $registry;

    public function __construct(ContextAccessor $contextAccessor, ManagerRegistry $registry)
    {
        $this->contextAccessor = $contextAccessor;

        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    protected function executeAction($context)
    {
        $this->logger->error("baaaaaa");
        $checkout = $this->contextAccessor->getValue($context, $this->checkout);
        if (!is_object($checkout)) {
            throw new InvalidParameterException(
                sprintf(
                    'Action "%s" expects reference to entity as parameter, %s is given.',
                    static::NAME,
                    gettype($checkout)
                )
            );
        }
        $shoppingListItems = $checkout->getSourceEntity()->getLineItems();
        if (null === $shoppingListItems) {
            return;
        }
        $checkoutItems = $checkout->getLineItems();
        foreach ($shoppingListItems as $item) {
            foreach ($checkoutItems as $checkoutItem) {
                if ($item->getProduct()->getSku() == $checkoutItem->getProduct()->getSku()) {
                    $item->setOrder($this->order);
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(array $options)
    {
        if (2 == count($options)) {
            $this->checkout = $options[0];
            $this->order = $options[1];
        } else {
            throw new InvalidParameterException(
                sprintf(
                    'Parameters of "%s" action must have 2 element, but %d given',
                    static::NAME,
                    count($options)
                )
            );
        }

        return $this;
    }

    /**
     * @param string $entityClassName
     * @return EntityManager
     * @throws NotManageableEntityException
     */
    protected function getEntityManager($entityClassName)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->registry->getManagerForClass($entityClassName);
        if (!$entityManager) {
            throw new NotManageableEntityException($entityClassName);
        }

        return $entityManager;
    }
}
