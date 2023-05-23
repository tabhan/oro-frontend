define([
    'oroworkflow/js/transition-executor',
    'oroui/js/mediator'
], function(executor, mediator) {
    'use strict';

    /**
     * Transition executor
     *
     * @export  oroworkflow/js/transition-executor
     * @class   oro.WorkflowTransitionExecutor
     */
    return function(element, data, pageRefresh) {
        const event = {element, data};
        mediator.trigger('workflow:transition:execute', event);
        executor(element, event.data, pageRefresh);
    };
});
