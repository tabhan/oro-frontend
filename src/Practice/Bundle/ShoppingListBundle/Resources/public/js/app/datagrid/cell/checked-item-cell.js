import BooleanCell from 'oro/datagrid/cell/boolean-cell';

const CheckedItemCell = BooleanCell.extend({
    onRowClicked(row, e) {
        if (!this.model.get('order_identifier')) {
            CheckedItemCell.__super__.onRowClicked.call(this, row, e);
        }
    },

    enterEditMode() {
        if (!this.currentEditor) {
            CheckedItemCell.__super__.enterEditMode.call(this);
            if (this.model.get('order_identifier')) {
                this.currentEditor.$el.prop("disabled", true);
            }
        }
    },
});

export default CheckedItemCell;
