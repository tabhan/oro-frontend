define([
    'oroui/js/mediator',
    'oroworkflow/js/transition-executor',
], function (mediator, executor) {
    return function (element, data, pageRefresh) {
        const event = {element, data};
        mediator.trigger('workflow:transition:execute:data:setting', event);
        executor(element, event.data, pageRefresh);
    }
});
