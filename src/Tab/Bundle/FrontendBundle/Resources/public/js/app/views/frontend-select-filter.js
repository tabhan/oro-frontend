define([
    'orofrontend/js/app/views/frontend-select-filter',
    'underscore',
], function(BaseFilter, _) {

    const FrontendSelectFilter = BaseFilter.extend({

        initialize() {
            FrontendSelectFilter.__super__.initialize.apply(this, arguments);
            const {icons} = this;
            this.widgetOptions = _.extend({}, this.widgetOptions, {icons});
        },
    });

    return FrontendSelectFilter;
});