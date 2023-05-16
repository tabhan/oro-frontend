<?php

namespace Tab\Bundle\FrontendBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\AttachmentBundle\Migration\Extension\AttachmentExtensionAwareInterface;
use Oro\Bundle\AttachmentBundle\Migration\Extension\AttachmentExtensionAwareTrait;
use Oro\Bundle\EntityExtendBundle\EntityConfig\ExtendScope;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\ProductBundle\Migrations\Schema\OroProductBundleInstaller;

class TabFrontendBundleInstaller implements Installation, AttachmentExtensionAwareInterface
{

    use AttachmentExtensionAwareTrait;

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
    }
}
