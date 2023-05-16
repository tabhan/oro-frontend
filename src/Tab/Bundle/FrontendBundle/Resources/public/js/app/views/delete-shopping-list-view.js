import BaseView from 'oroui/js/app/views/base/view';
import StandardConfirmation from 'oroui/js/standart-confirmation';
import _ from 'underscore';
import $ from 'jquery';
import ShoppingListCollectionService from 'oroshoppinglist/js/shoppinglist-collection-service';

const DeleteShoppingListView = BaseView.extend({

    shoppingListCollection: null,

    confirmModal: null,

    shoppingListId: null,

    optionNames: BaseView.prototype.optionNames.concat(['shoppingListId']),

    events: {
        click: 'onClick',
    },

    initialize(options) {
        DeleteShoppingListView.__super__.initialize.apply(this, arguments);
        ShoppingListCollectionService.shoppingListCollection.done(collection => this.shoppingListCollection = collection);
    },

    onClick() {
        const confirmView = this.subview('confirmView') || (new StandardConfirmation({
            title: _.__('oro.frontend.shoppinglist.dialog.delete_confirmation.label'),
            content: _.__('oro.frontend.shoppinglist.dialog.delete_confirmation.message'),
            okText: _.__('Yes, Delete'),
        })).on('ok', _.bind(this.onConfirmModalOk, this));
        confirmView.open();
    },

    onConfirmModalOk() {
        $.ajax({
            type: 'DELETE',
            url: `/api/shoppinglists/${this.shoppingListId}`,
            success: () => {
                const shoppingList = this.shoppingListCollection.find({id: this.shoppingListId});
                if (shoppingList) {
                    if (shoppingList.get('is_current')) {
                        location.reload();
                    } else {
                        this.shoppingListCollection.remove(shoppingList, {silent: true});
                        this.shoppingListCollection.trigger('change', {refresh: true});
                    }
                }
            },
            complete: () => {
                const confirmView = this.subview('confirmView');
                confirmView && confirmView.close();
            },
        });
    },
});

export default DeleteShoppingListView;