<?php

namespace Practice\Bundle\ProductBundle\EventListener;

use Doctrine\ORM\EntityRepository;
use Oro\Bundle\DataGridBundle\Datagrid\Common\DatagridConfiguration;
use Oro\Bundle\DataGridBundle\Event\PreBuild;
use Oro\Bundle\PricingBundle\Entity\PriceList;
use Oro\Bundle\PricingBundle\Filter\PriceListsFilter;
use Oro\Bundle\ProductBundle\Entity\Brand;
use Oro\Bundle\TerritoryBundle\Entity\Territory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class SearchProductBrandFilteringEventListener
{

    public function onPreBuild(PreBuild $event)
    {

        $config = $event->getConfig();

        $this->addProductBrandListFilter($config);
    }





    protected function addProductBrandListFilter(DatagridConfiguration $datagridConfiguration)
    {

        $datagridConfiguration->addFilter(
            'brand',
            [
                'label' => 'oro.product.brand.label',
                'type' => 'product-brand-list',
                'data_name' => 'integer.brand',
                'translatable' => true,
                'options' => [
                    'class' => Brand::class,
                ]
            ]
        );
    }

}
