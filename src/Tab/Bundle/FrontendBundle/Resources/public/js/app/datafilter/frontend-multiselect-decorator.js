define([
    'orofrontend/js/app/datafilter/frontend-multiselect-decorator',
    'underscore',
], function(BaseDecorator, _) {

    const FrontendMultiSelectDecorator = function(options) {
        BaseDecorator.call(this, options);
    };

    FrontendMultiSelectDecorator.prototype = _.extend(Object.create(BaseDecorator.prototype), {
        constructor: FrontendMultiSelectDecorator,

        setDesignForCheckboxesDefaultTheme: function ({options: {icons = []}, menu}) {
            BaseDecorator.prototype.setDesignForCheckboxesDefaultTheme.apply(this, arguments);
            _.each(icons, (src, id) => {
                if (!menu.find(`img[data-id=${id}]`).length) {
                    menu.find(`input[value=${id}]`).after(`<img class="brand-icon" data-id="${id}" alt="" src="${src}">`);
                }
            })
        },
    });

    return FrontendMultiSelectDecorator;
});