<?php

namespace Practice\Bundle\CheckoutBundle\Action;

use Oro\Bundle\CheckoutBundle\Entity\Checkout;
use Oro\Bundle\ShoppingListBundle\Entity\ShoppingList;
use Psr\Log\LoggerAwareTrait;

class CheckedItemAction
{
    use LoggerAwareTrait;

    public function beforeStartCheckout(ShoppingList $shoppingList, $checkedItemStr)
    {
        $checkedItems = json_decode($checkedItemStr, true);
        $this->lineItems = $shoppingList->getLineItems()->toArray();
        $shoppingList = $shoppingList;
        $shoppingList->getLineItems()->clear();
        foreach ($this->lineItems as $item) {
            if ($this->isCheckedItem($checkedItems, $item)) {
                $shoppingList->addLineItem($item);
            }
        }
    }

    public function afterStartCheckout(ShoppingList $shoppingList)
    {
        foreach ($this->lineItems as $item) {
            $shoppingList->addLineItem($item);
        }
    }

    public function linkOrderToShoppingListItem(Checkout $checkout, $order)
    {
        $this->logger->error("aaaaaa");
        $shoppingListItems = $checkout->getSourceEntity()->getLineItems()->toArray();
        $checkoutItems = $checkout->getLineItems();
        foreach ($shoppingListItems as $item) {
            foreach ($checkoutItems as $checkoutItem) {
                if ($item->getProduct()->getSku() == $checkoutItem->getProduct()->getSku()) {
                    $item->setOrder($order);
                }
            }
        }

    }

    protected function isCheckedItem($checkedItems, $item)
    {
        return array_filter($checkedItems, function($checkedItem) use($item) {
            return $checkedItem === $item->getproduct()->getSku();
        });
    }
}
