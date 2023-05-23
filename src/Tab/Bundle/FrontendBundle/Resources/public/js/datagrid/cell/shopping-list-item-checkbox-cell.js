define([
    'oro/datagrid/cell/boolean-cell',
    'underscore'
], function(BaseCell, _) {
    'use strict';

    const CheckboxCell = BaseCell.extend({

        enterEditMode() {
            CheckboxCell.__super__.enterEditMode.call(this);
            if (this.model.get('orderId')) {
                this.currentEditor.$el.prop('disabled', true);
                this.currentEditor.off('change');
            }
        },

        onRowClicked(row, e) {
            if (!this.model.get('orderId')) {
                CheckboxCell.__super__.onRowClicked.call(this, row, e);
            }
        }
    });

    return CheckboxCell;
});