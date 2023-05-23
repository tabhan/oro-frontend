<?php

namespace Tab\Bundle\FrontendBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\AttachmentBundle\Migration\Extension\AttachmentExtensionAwareInterface;
use Oro\Bundle\AttachmentBundle\Migration\Extension\AttachmentExtensionAwareTrait;
use Oro\Bundle\EntityBundle\EntityConfig\DatagridScope;
use Oro\Bundle\EntityExtendBundle\EntityConfig\ExtendScope;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtension;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class TabFrontendBundleInstaller implements
    Installation,
    AttachmentExtensionAwareInterface,
    ExtendExtensionAwareInterface
{

    use AttachmentExtensionAwareTrait;

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
        $this->attachmentExtension->addImageRelation(
            $schema,
            OroProductBundleInstaller::BRAND_TABLE_NAME,
            'icon',
            [
                'attachment' => [
                    'acl_protected' => false,
                    'use_dam' => true,
                ],
                'extend' => ['owner' => ExtendScope::OWNER_CUSTOM],
            ]
        );

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

        $table->addColumn('checked', 'boolean', [
            'notnull' => false,
            'oro_options' => [
                'extend' => [
                    'is_extend' => true,
                    'owner' => ExtendScope::OWNER_SYSTEM,
                ],
                'datagrid' => [
                    'is_visible' => DatagridScope::IS_VISIBLE_FALSE,
                    'show_filter' => false,
                ],
            ]
        ]);
        $table->addColumn('ordinal', 'integer', [
            'notnull' => false,
            'oro_options' => [
                'extend' => [
                    'is_extend' => true,
                    'owner' => ExtendScope::OWNER_SYSTEM,
                ],
                'datagrid' => [
                    'is_visible' => DatagridScope::IS_VISIBLE_FALSE,
                    'show_filter' => false,
                ],
            ]
        ]);
    }
}
