import BaseClass from 'oroui/js/base-class';

export const eventBroker = new BaseClass();

export default {

    /**
     * Init() function is required
     */
    init(deferred, {gridPromise}) {
        gridPromise.done(({collection}) => {
            collection.on('reset', () => {
                eventBroker.trigger('reset', collection.state);
            })
            eventBroker.trigger('reset', collection.state);
            deferred.resolve();
        }).fail(function() {
            deferred.reject();
        });
    }
};
