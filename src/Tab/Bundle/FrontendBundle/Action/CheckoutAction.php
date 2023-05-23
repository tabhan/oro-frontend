<?php

namespace Tab\Bundle\FrontendBundle\Action;

use Oro\Bundle\CheckoutBundle\Entity\Checkout;
use Oro\Bundle\OrderBundle\Entity\Order;
use Oro\Bundle\OrderBundle\Entity\OrderLineItem;
use Oro\Bundle\ShoppingListBundle\Entity\LineItem;
use Oro\Bundle\ShoppingListBundle\Entity\ShoppingList;

class CheckoutAction
{

    /** @var array|null */
    protected array|null $lineItems;


    public function beforeStartCheckout(ShoppingList $shoppingList, $itemsInfo): void
    {
        if (!is_string($itemsInfo)) {
            return;
        }
        $itemsInfo = json_decode($itemsInfo, true);
        $this->lineItems = $shoppingList->getLineItems()->toArray();
        $checkedItems = [];
        /** @var LineItem $lineItem */
        foreach ($this->lineItems as $lineItem) {
            $id = $lineItem->getId();
            $checked = $itemsInfo[$id]['checked'] ?? null;
            $ordinal = $itemsInfo[$id]['ordinal'] ?? null;
            if (is_null($ordinal)) {
                // because it's array key.
                return;
            }
            if ($checked) {
                $checkedItems[$ordinal] = $lineItem;
            }
            $lineItem
                ->setChecked($checked)
                ->setOrdinal($ordinal);
        }
        ksort($checkedItems);
        $shoppingList->getLineItems()->clear();
        foreach ($checkedItems as $lineItem) {
            !$lineItem->getOrder() && $shoppingList->addLineItem($lineItem);
        }
    }

    public function afterStartCheckout(ShoppingList $shoppingList): void
    {
        foreach ($this->lineItems as $lineItem) {
            $shoppingList->addLineItem($lineItem);
        }
        $this->lineItems = null;
    }

    /**
     * @param Checkout $checkout
     * @param Order $order
     * @return void
     */
    public function linkOrderToShoppingListItem(Checkout $checkout, Order $order): void
    {
        $sourceEntity = $checkout->getSourceEntity();
        if (!$sourceEntity instanceof ShoppingList) {
            return;
        }
        $shoppingListLineItems = $sourceEntity->getLineItems();
        $orderLineItems = $order->getLineItems()->toArray();
        $products = array_map(function (OrderLineItem $orderLineItem) {
            return $orderLineItem->getProduct();
        }, $orderLineItems);
        /** @var LineItem $item */
        foreach ($shoppingListLineItems as $item) {
            if (in_array($item->getProduct(), $products)) {
                $item->setOrder($order);
            }
        }
    }
}
