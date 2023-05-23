define([
    'underscore'
], function(_) {
    'use strict';

    return {

        init(deferred, {gridPromise}) {
            gridPromise.done(({body: {$el}}) => {
                let extRow;
                $el.sortable({
                    items: '.grid-row:not(.extension-row)',
                    start: (e, {placeholder}) => extRow = placeholder.next('.extension-row'),
                    update: (e, {item}) => item.after(extRow),
                });
                deferred.resolve();
            }).fail(function() {
                deferred.reject();
            });
        },
    };
});
