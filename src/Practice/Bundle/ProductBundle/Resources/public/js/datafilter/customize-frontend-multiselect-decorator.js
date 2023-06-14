define(function(require, exports, module) {
    'use strict';

    const _ = require('underscore');
    const __ = require('orotranslation/js/translator');
    const $ = require('jquery');
    const FrontendMultiSelectDecorator = require('orofrontend/js/app/datafilter/frontend-multiselect-decorator');
    let config = require('module-config').default(module.id);

    const CustomizeFrontendMultiSelectDecorator = function(options) {
        const params = _.pick(options.parameters, ['additionalClass', 'hideHeader', 'themeName', 'listAriaLabel']);

        if (!_.isEmpty(params)) {
            this.parameters = _.extend({}, this.parameters, params);
        }

        FrontendMultiSelectDecorator.call(this, options);
    };

    CustomizeFrontendMultiSelectDecorator.prototype = _.extend(Object.create(FrontendMultiSelectDecorator.prototype), {

        constructor: CustomizeFrontendMultiSelectDecorator,
        /**
         * @param {object} instance
         */
        setDesignForCheckboxesDefaultTheme: function(instance) {
            // CustomizeFrontendMultiSelectDecorator.__super__.setDesignForCheckboxesDefaultTheme.call(this, instance);
            instance.menu
                .children('.ui-multiselect-checkboxes')
                .removeClass('ui-helper-reset')
                .addClass('datagrid-manager__list ui-rewrite')
                .find('li')
                .addClass('datagrid-manager__list-item');

            instance.labels.addClass('checkbox-label');

            const items = instance.menu
                .children('.ui-multiselect-checkboxes')
                .find('li');

            _.each(items, (item) => {
                if (!$($(item).find('span').prev()).is('img')) {
                    const option = _.filter(instance.element.find('option'), (opt) => {
                        return $(item).find('input').val() == opt.value;
                    }, [item]);
                    if (option.length >= 1) {
                        $(item).find('span').before('<img style="width: 22px" src="' + option[0].dataset.imageUrl + '"/>');
                    }
                }
            });
        },

        dispose() {
            $(`[data-cid="menu-${this.cid}"]`).remove();

            return FrontendMultiSelectDecorator.prototype.dispose.call(this);
        }
    });

    return CustomizeFrontendMultiSelectDecorator;
});
