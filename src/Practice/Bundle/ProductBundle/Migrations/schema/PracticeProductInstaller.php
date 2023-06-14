<?php

namespace Practice\Bundle\ProductBundle\Migrations\schema;

use Oro\Bundle\AttachmentBundle\Migration\Extension\AttachmentExtensionAwareInterface;
use Oro\Bundle\AttachmentBundle\Migration\Extension\AttachmentExtensionAwareTrait;
use Oro\Bundle\EntityExtendBundle\EntityConfig\ExtendScope;
use Oro\Bundle\EntitySerializedFieldsBundle\Migration\Extension\SerializedFieldsExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\EntitySerializedFieldsBundle\Migration\Extension\SerializedFieldsExtension;
use Oro\Bundle\ProductBundle\Migrations\Schema\OroProductBundleInstaller;
use Doctrine\DBAL\Schema\Schema;
use \Oro\Bundle\MigrationBundle\Migration\QueryBag;

class PracticeProductInstaller
implements
Installation,
SerializedFieldsExtensionAwareInterface,
AttachmentExtensionAwareInterface
{
    use AttachmentExtensionAwareTrait;

    /** @var SerializedFieldsExtension */
    protected $serializedFieldsExtension;

    /**
     * @inheritDoc
     */
    public function setSerializedFieldsExtension(SerializedFieldsExtension $serializedFieldsExtension)
    {
        $this->serializedFieldsExtension = $serializedFieldsExtension;
    }

    /**
     * {@inheritDoc}
     */
    public function getMigrationVersion()
    {
        return 'v1_0';
    }

    /**
     * {@inheritDoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $this->extendBrand($schema);
    }

    public function extendBrand(Schema $schema)
    {
        $this->attachmentExtension->addImageRelation(
            $schema,
            OroProductBundleInstaller::BRAND_TABLE_NAME,
            'image',
            [
                'extend' => ['owner' => ExtendScope::OWNER_CUSTOM],
                'importexport' => [
                    'excluded' => false,
                ],
                'attachment' => [
                    'acl_protected' => false,
                    'use_dam' => false,
                ]
            ],
            10
        );
    }
}
