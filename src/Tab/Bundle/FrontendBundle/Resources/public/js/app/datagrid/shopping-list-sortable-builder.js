define([
    'underscore'
], function(_) {
    'use strict';

    return {

        collection: null,

        checkingRequired: false,

        processDatagridOptions(deferred, options) {
            _.each(options.data.data, (e, i) => {
                e.ordinal = i;
                e.row_attributes = {'data-id': e.id};
            });
            deferred.resolve();
        },

        init(deferred, {gridPromise}) {
            gridPromise.done(({collection, body: {$el}}) => {
                this.collection = collection;
                this.checkRequired(collection.first());
                let extRow;
                $el.sortable({
                    items: '.grid-row:not(.extension-row)',
                    start: (e, {placeholder}) => extRow = placeholder.next('.extension-row'),
                    update: (e, {item}) => {
                        item.after(extRow);
                        _.each($el.sortable('instance').items, ({item: e}) => {
                            // Update ordinal for each line item
                            const id = e.data('id');
                            collection.get(id).set('ordinal', e.index(), {silent: true});
                        });
                        this.checkRequired(collection.get(item.data('id')), true);
                    },
                });
                deferred.resolve();
            }).fail(function() {
                deferred.reject();
            });
        },

        checkRequired(targetModel, ddMode = false) {
            if (this.checkingRequired) {
                return;
            }
            this.checkingRequired = true;
            this.collection.each(model => {
                if (!model.get('ordinal')) {
                    // always check the first one
                    model.set('checked', true);
                    return;
                }
                const targetChecked = targetModel.get('checked');
                const targetOrdinal = targetModel.get('ordinal');
                const checked = model.get('checked');
                const ordinal = model.get('ordinal');

                if (targetChecked && ordinal < targetOrdinal) {
                    // DD checked row will make others checked
                    model.set('checked', true);
                } else if (!targetChecked && ordinal > targetOrdinal) {
                    if (!ddMode) {
                        model.set('checked', false);
                    } else if (checked) {
                        // DD un checked row will make it self checked
                        targetModel.set('checked', true);
                    }
                }
            });
            this.checkingRequired = false;
        },
    };
});
