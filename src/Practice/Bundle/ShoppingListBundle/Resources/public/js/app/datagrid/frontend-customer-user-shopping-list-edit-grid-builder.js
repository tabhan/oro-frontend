import 'jquery-ui/ui/widgets/droppable';
import mediator from 'oroui/js/mediator';
import _ from 'underscore';

export default {
    collection: null,

    processDatagridOptions(deferred, options) {
        _.each(options.data.data, (e, i) => _.extend(e, {
            ordinal: i,
            row_attributes: {'data-id': e.id},
            checked: e.checked || !!e.order_identifier,
            row_class_name: `${e.row_class_name} ${e.order_identifier ? '' : 'draggable_row'}`,
        }));
        deferred.resolve();
    },

    init(deferred, options) {
        options.gridPromise.done(grid=>{
            let collection = grid.collection;
            this.collection = collection;
            this.checkedItem(_.filter(this.collection.models, model => {
                if (!model.get('order_identifier')) {
                    return model;
                }
            })[0]);
            mediator.on("checked-item", (sku)=>{
                //alert(sku);
            });
            mediator.on("workflow:transition:execute:data:setting", event => {
                let checkedItems = _.filter(this.collection.models, model => {
                    if (model.attributes['checked_item']) {
                        return model;
                    }
                });
                let skus = _.map(checkedItems, item => {
                    return item.attributes.sku;
                });
                event.data = JSON.stringify({shopping_list_checked_items: JSON.stringify(skus)});
            });
            collection.on("change:checked_item", model => {
                this.checkedItem(model);
            });
            $('tbody tr[class*="draggable_row"]').draggable({
                helper: "clone",
                start: function(event, ui) {
                    $(this).addClass("dragging");
                },
                stop: function(event, ui) {
                    $(this).removeClass("dragging");
                }
            });
            $('tbody tr[class*="draggable_row"]').droppable({
                accept: ".dragging",
                drop: (event, ui) => {
                    let draggableRow = ui.draggable;
                    let aria_hidden_element = draggableRow.next();
                    let droppableRow = $(event.target);

                    draggableRow.insertAfter(droppableRow);

                    if (aria_hidden_element.attr('aria-hidden')) {
                        aria_hidden_element.insertAfter(draggableRow);
                    }

                    _.each($('tbody tr[class*="draggable_row"]'), (item) => {
                        let id = $(item).data('id');
                        if (!$(item).hasClass('mouse-down')) {
                            this.collection.get(id).set('ordinal', $(item).index(), {silent: true});
                        }
                    });
                }
            });
            deferred.resolve();
        }).fail(function () {
            deferred.reject();
        });
    },

    checkedItem: function(model) {
        let collection = model.collection;
        if (model.get("checked_item") == null) {
            model.set('checked_item', true);
        }
        collection.each(item => {
            if (!item.get('order_identifier') && item.get('ordinal') < model.get('ordinal')) {
                item.set('checked_item', model.get('checked_item'));
            }
        });
    }
};
