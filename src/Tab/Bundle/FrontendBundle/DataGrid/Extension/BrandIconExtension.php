<?php

namespace Tab\Bundle\FrontendBundle\DataGrid\Extension;

use Doctrine\DBAL\Connection;
use Oro\Bundle\AttachmentBundle\Entity\File;
use Oro\Bundle\AttachmentBundle\Manager\AttachmentManager;
use Oro\Bundle\DataGridBundle\Datagrid\Common\DatagridConfiguration;
use Oro\Bundle\DataGridBundle\Datagrid\Common\MetadataObject;
use Oro\Bundle\DataGridBundle\Extension\AbstractExtension;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\ProductBundle\Entity\Brand;

class BrandIconExtension extends AbstractExtension
{

    /** @var AttachmentManager */
    protected AttachmentManager $attachmentManager;

    /** @var DoctrineHelper */
    protected DoctrineHelper $doctrineHelper;

    /**
     * @param AttachmentManager $attachmentManager
     * @param DoctrineHelper $doctrineHelper
     */
    public function __construct(AttachmentManager $attachmentManager, DoctrineHelper $doctrineHelper)
    {
        $this->attachmentManager = $attachmentManager;
        $this->doctrineHelper = $doctrineHelper;
    }


    public function isApplicable(DatagridConfiguration $config)
    {
        return $config->getName() === 'frontend-product-search-grid' && parent::isApplicable($config);
    }

    public function visitMetadata(DatagridConfiguration $config, MetadataObject $data)
    {
        /** @var array $filters */
        $filters = $data->offsetGetByPath('[filters]', []);
        foreach ($filters as $key => $filter) {
            if (($filter['name'] ?? null) === 'brand') {
                $ids = array_column($filter['choices'] ?? [], 'value');
                $data->offsetSetByPath("[filters][$key][icons]", $this->getIcons($ids));
            }
        }
    }

    /**
     * @param array $brandIds
     * @return array
     */
    protected function getIcons(array $brandIds): array
    {
        $qb = $this->doctrineHelper->createQueryBuilder(File::class, 'f', 'f.parentEntityId');
        $qb
            ->andWhere($qb->expr()->eq('f.parentEntityClass', ':class'))
            ->andWhere($qb->expr()->in('f.parentEntityId', ':brandIds'))
            ->setParameter('class', Brand::class)
            ->setParameter('brandIds', $brandIds, Connection::PARAM_INT_ARRAY);
        $files = $qb->getQuery()->getResult();
        return array_map(function (File $file) {
            return $this->attachmentManager->getFilteredImageUrl($file, 'original');
        }, $files);
    }
}
