import BasePlugin from 'oroshoppinglist/js/datagrid/plugins/shopping-list-inline-editing-plugin';

const ShoppingListInlineEditingPlugin = BasePlugin.extend({

    patchCellConstructor(column) {
        ShoppingListInlineEditingPlugin.__super__.patchCellConstructor.call(this, column);
        let cell = column.get('cell');
        switch (column.get('name')) {
            case 'quantity':
                cell = cell.extend({
                    render() {
                        cell.__super__.render.apply(this, arguments);
                        if (this.model.get('orderId')) {
                            this.$el.addClass('read-only');
                        }
                        return this;
                    },
                });
                break;
            case '':
                // Action cell doesn't have name.
                cell = cell.extend({
                    createLaunchers() {
                        return this.model.get('orderId') ? []
                            : cell.__super__.createLaunchers.apply(this, arguments);
                    },
                });
                break;
            default:
                break;
        }

        column.set('cell', cell);
    },

    isEditable({model}) {
        return !model.get('orderId') && ShoppingListInlineEditingPlugin.__super__.isEditable.apply(this, arguments);
    },
});

export default ShoppingListInlineEditingPlugin;