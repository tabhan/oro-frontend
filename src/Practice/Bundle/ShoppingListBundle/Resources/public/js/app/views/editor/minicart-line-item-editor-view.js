import _ from 'underscore';
import TextEditorView from 'oroform/js/app/views/editor/text-editor-view';
import mediator from 'oroui/js/mediator';
import ApiAccessor from 'oroui/js/tools/api-accessor';

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

const MinicartLineItemEditorView = TextEditorView.extend({
    events: {
        'input input[name="quantity"]': 'changeQuantity',
        'click input[name="quantity"]': 'changeFocus',
        'click div[name="unitCode"]': 'showSelectDrop',
        'click li[name="item-unit"]': 'updateUnit',
    },

    template: require('tpl-loader!practiceshoppinglist/templates/editor/shoppinglist-line-item-editor.html'),

    unit: null,
    units: [],
    quantity: null,
    lineItemId: null,
    orderId: null,

    constructor: function ShoppinglistLineItemEditorView(...args) {
        MinicartLineItemEditorView.__super__.constructor.apply(this, args);
    },

    initialize(options) {
        MinicartLineItemEditorView.__super__.initialize.call(this, options);
        _.extend(this, _.pick(options, 'unit', 'units', 'quantity', 'lineItemId', 'orderId'));
    },

    getTemplateData() {
        return {
            unit: this.unit,
            quantity: this.quantity,
            units: this.units,
            orderId: this.orderId,
        };
    },

    isChanged() {
        return false;
    },

    showSelectDrop(target) {
        this.$('.select2-drop').toggleClass('hide');
        target.currentTarget.focus();
    },

    updateUnit(target) {
        this.$('.select2-drop').toggleClass('hide');
        if (this.unit != target.currentTarget.dataset.unit) {
            this.unit = target.currentTarget.dataset.unit;
            this.updateItem();
        }
    },

    changeQuantity(target) {
        if (this.quantity != target.currentTarget.value && target.currentTarget.value.length != 0) {
            this.quantity = target.currentTarget.value;
            this.updateItem();
        }
    },

    changeFocus(target) {
        target.currentTarget.focus();
    },

    updateItem() {
        const unit = this.unit;
        const lineItemId = this.lineItemId;
        const quantity = this.quantity;
        const apiAccessor = quantity > 0 ? updateApiAccessor : deleteApiAccessor;
        apiAccessor.send({id: lineItemId}, {quantity, unit}).done(response => {
            this.$('.select').html(unit);
            mediator.trigger('datagrid:doRefresh:frontend-customer-user-shopping-list-edit-grid');
            mediator.trigger('datagrid:doRefresh:frontend-customer-user-shopping-list-grid');
            mediator.trigger('frontend:shopping-list-item-quantity:update');
            mediator.trigger('datagrid:doRefresh:frontend-product-search-grid');
            mediator.trigger('layout-subtree:update:product');
        });
    },
});

export default MinicartLineItemEditorView;
