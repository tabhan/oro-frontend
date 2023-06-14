import BaseView from 'oroui/js/app/views/base/view';
import _ from 'underscore';

const quantityUpdateView = BaseView.extend({
    events: {
        'change input': 'onQuantityChange',
    },

    shoppingListId: null,
    productId: null,
    unit: null,
    lineItemId: null,

    initialize(options) {
        _.extend(this, _.pick(options, 'shoppingListId', 'lineItemId', 'unit', 'productId'));
    },

    onQuantityChange() {
        _.delay(this.$el.trigger('updateProductQuantity'), 500);
    },
});

export default quantityUpdateView;
