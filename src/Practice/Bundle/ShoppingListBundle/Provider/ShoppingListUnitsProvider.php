<?php

namespace Practice\Bundle\ShoppingListBundle\Provider;

class ShoppingListUnitsProvider
{
    public function getCurrentShoppingListUnits($shoppingLists)
    {
        $currentShoppingList = $shoppingLists ? $shoppingLists[0] : null;

        $units = [];
        $lineItems = $currentShoppingList?->getLineItems() ?? [];
        foreach ($lineItems as $lineItem) {
            $list = [];
            $product = $lineItem->getProduct();
            foreach ($product->getUnitPrecisions() as $unitPrecision) {
                if (!$unitPrecision->isSell()) {
                    continue;
                }

                $list[$unitPrecision->getUnit()->getCode()] = $unitPrecision->getUnit()->getCode();
            }
            $units[$product->getId()] = $list;
        }


        return $units;
    }
}
