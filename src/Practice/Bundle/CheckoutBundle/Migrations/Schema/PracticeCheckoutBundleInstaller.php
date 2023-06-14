<?php

namespace Practice\Bundle\CheckoutBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\EntityBundle\EntityConfig\DatagridScope;
use Oro\Bundle\EntityExtendBundle\EntityConfig\ExtendScope;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtension;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class PracticeCheckoutBundleInstaller implements Installation, ExtendExtensionAwareInterface
{

    /**
     * @var ExtendExtension
     */
    protected ExtendExtension $extendExtension;

    /**,
     * {@inheritdoc}
     */
    public function setExtendExtension(ExtendExtension $extendExtension)
    {
        $this->extendExtension = $extendExtension;
    }


    public function getMigrationVersion()
    {
        return 'v1_0';
    }

    public function up(Schema $schema, QueryBag $queries)
    {
        $table = $schema->getTable('oro_shopping_list_line_item');
        $this->extendExtension->addManyToOneRelation(
            $schema,
            $table,
            'order',
            'oro_order',
            'id',
            [
                'entity' => ['label' => 'oro.order.entity_label'],
                'extend' => [
                    'is_extend' => true,
                    'owner' => ExtendScope::OWNER_CUSTOM
                ],
                'datagrid' => [
                    'is_visible' => DatagridScope::IS_VISIBLE_FALSE,
                ],
                'form' => [
                    'is_enabled' => false
                ],
                'view' => ['is_displayable' => false],
                'merge' => ['display' => false],
                'dataaudit' => ['auditable' => false]
            ]
        );
    }
}
