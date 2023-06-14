<?php

namespace Practice\Bundle\CheckoutBundle\Condition;

use Oro\Bundle\CheckoutBundle\Entity\Checkout;
use Oro\Component\ConfigExpression\Condition\AbstractCondition;
use Oro\Component\ConfigExpression\ContextAccessorAwareInterface;
use Oro\Component\ConfigExpression\ContextAccessorAwareTrait;
use Oro\Component\ConfigExpression\Exception\InvalidArgumentException;
use Psr\Log\LoggerAwareTrait;

class ValidateEmptySource extends AbstractCondition implements ContextAccessorAwareInterface
{
    use ContextAccessorAwareTrait;

    use LoggerAwareTrait;

    const NAME = 'validate_checkout_remove_source';

    /**
     * @var mixed
     */
    protected $checkout;

    /**
     * {@inheritdoc}
     *
     * @return boolean
     */
    protected function doEvaluate($context)
    {
        return $this->isConditionAllowed($context);
    }

    protected function isConditionAllowed($context)
    {

        /** @var Checkout $checkout */
        $checkout = $this->resolveValue($context, $this->checkout, true);
        if (!$checkout instanceof Checkout) {
            return false;
        }
        $leftItems = array_filter($checkout->getSourceEntity()->getLineItems()->toArray(), function($item) {
            return $item->getOrder() == null;
        });
        $result = sizeof($leftItems) == 0;

        $this->logger->error("the result is: ". $result);
        return $result;
    }

    public function getName()
    {
        return self::NAME;
    }

    public function initialize(array $options)
    {
        if (array_key_exists('checkout', $options)) {
            $this->checkout = $options['checkout'];
        } elseif (array_key_exists(0, $options)) {
            $this->checkout = $options[0];
        }

        if (!$this->checkout) {
            throw new InvalidArgumentException('Missing "checkout" option');
        }

        return $this;
    }

    public function toArray()
    {
        return $this->convertToArray([$this->checkout]);
    }

    public function compile($factoryAccessor)
    {
        return $this->convertToPhpCode([$this->checkout], $factoryAccessor);
    }
}
