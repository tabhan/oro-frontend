import mediator from 'oroui/js/mediator';

export default {
    init(deferred, options) {
        options.gridPromise.done(grid=>{
            const {collection} = grid;
            const totalRecord = collection.options.totalRecords;
            mediator.trigger('update-title-with-total', totalRecord);
            const onReset = (c) => {
                mediator.trigger('update-title-with-total', c.state.totalRecords);
            };
            collection.on('reset', onReset);
            deferred.resolve();
        }).fail(function () {
            deferred.reject();
        });
    }
};
