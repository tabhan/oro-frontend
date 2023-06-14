import BaseView from 'oroui/js/app/views/base/view';
import _ from "underscore/underscore-node.mjs";


const FrontendProductSearchTitle = BaseView.extend({
    listen: {
        'update-title-with-total mediator': 'updateTotal',
    },

    title: null,

    initialize (options) {
        this.options = options;
        _.extend(this, _.pick(options, 'title'));
    },

    updateTotal(total) {
        this.$el.html(`${this.title} (${total} total records)`);
    },
});

export default FrontendProductSearchTitle;
