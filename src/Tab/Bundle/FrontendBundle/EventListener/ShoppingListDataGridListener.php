<?php

namespace Tab\Bundle\FrontendBundle\EventListener;

use Oro\Bundle\DataGridBundle\Event\BuildBefore;

class ShoppingListDataGridListener
{

    /**
     * @param BuildBefore $event
     * @return void
     */
    public function onBuildBefore(BuildBefore $event): void
    {
        $event->getConfig()->offsetUnsetByPath('mass_actions');
    }
}
