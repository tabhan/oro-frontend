<?php

namespace Practice\Bundle\ActionBundle\Layout\DataProvider;

use Oro\Bundle\ActionBundle\Layout\DataProvider\LayoutButtonProvider as Base;
use Oro\Bundle\ActionBundle\Model\OperationRegistry;
use Oro\Bundle\ActionBundle\Provider\ButtonProvider;
use Oro\Bundle\ActionBundle\Provider\ButtonSearchContextProvider;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;

class LayoutButtonProvider
{
    /** @var OperationRegistry */
    protected $operationRegistry;

    public function __construct(OperationRegistry $operationRegistry)
    {
        $this->operationRegistry = $operationRegistry;
    }

    public function getByName($actionName)
    {

        $this->operationRegistry->findByName($actionName);
        $context = $this->contextProvider->getButtonSearchContext();
        return $this->buttonProvider->findAvailable(
            $context->setGroup($actionName)
        );
    }
}
