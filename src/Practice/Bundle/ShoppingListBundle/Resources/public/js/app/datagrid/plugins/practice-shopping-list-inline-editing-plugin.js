import ShoppingListInlineEditingPlugin from 'oroshoppinglist/js/datagrid/plugins/shopping-list-inline-editing-plugin';

const PracticeShoppingListInlineEditingPlugin = ShoppingListInlineEditingPlugin.extend({

    isEditable(cell) {
        if (cell.model.get('order_identifier')) {
            return false;
        }

        return PracticeShoppingListInlineEditingPlugin.__super__.isEditable.call(this, cell);
    },
});

export default PracticeShoppingListInlineEditingPlugin;
