import BaseView from 'practiceproduct/js/app/views/quantity-update-view';
import ApiAccessor from 'oroui/js/tools/api-accessor';
import mediator from 'oroui/js/mediator';

const updateApiAccessor = new ApiAccessor({
    form_name: 'oro_product_frontend_line_item',
    http_method: 'PUT',
    route: 'oro_api_shopping_list_frontend_put_line_item',
});

const deleteApiAccessor = new ApiAccessor({
    form_name: 'oro_product_frontend_line_item',
    http_method: 'DELETE',
    route: 'oro_api_shopping_list_frontend_delete_line_item',
});

const quantityUpdateView = BaseView.extend({

    events: {
        'updateProductQuantity': 'onUpdateProductQuantity',
    },

    initialize(options) {
        quantityUpdateView.__super__.initialize.call(this, options);
    },

    onUpdateProductQuantity() {
        const unit = this.unit;
        const lineItemId = this.lineItemId;
        const quantity = parseInt(this.$('input').val());
        const apiAccessor = quantity > 0 ? updateApiAccessor : deleteApiAccessor;
        apiAccessor.send({id: lineItemId}, {quantity, unit}).done(response => {
            mediator.trigger('datagrid:doRefresh:frontend-customer-user-shopping-list-edit-grid');
            mediator.trigger('datagrid:doRefresh:frontend-customer-user-shopping-list-grid');
            mediator.trigger('frontend:shopping-list-item-quantity:update');
        });

    },
});

export default quantityUpdateView;
