define(function(require) {
    const DeleteProductAction = require('oro/datagrid/action/delete-product-action');

    const PracticeDeleteProductAction = DeleteProductAction.extend({
        execute: function() {
            if (this.model.get('order_identifier')) {
                return;
            }
            PracticeDeleteProductAction.__super__.execute.call(this);
        },
    });

    return PracticeDeleteProductAction;
});
