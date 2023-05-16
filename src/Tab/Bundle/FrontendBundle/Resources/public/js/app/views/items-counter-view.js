import BaseView from 'oroui/js/app/views/base/view';
import template from 'tpl-loader!orodatagrid/templates/datagrid/visible-items-counter.html';
import {eventBroker} from 'tabfrontend/js/app/datagrid/frontend-product-items-counter-builder';
import _ from 'underscore';

const ItemsCounterView = BaseView.extend({

    template,

    state: null,

    initialize() {
        ItemsCounterView.__super__.initialize.apply(this, arguments);
        this.listenTo(eventBroker, 'reset', state => {
            this.state = state;
            this.render();
        });
    },

    getTemplateData() {
        const {state} = this;
        const transTemplate = null;
        return {state, transTemplate};
    },
});

export default ItemsCounterView;