import FrontendProductPriceFilter from 'oropricing/js/filter/frontend-product-price-filter';

const PracticeFrontendProductPriceFilter = FrontendProductPriceFilter.extend({

    events: {
        'change input[name="value"]': '_onChangePriceValue',
        'change input[name="value_end"]': '_onChangePriceValue',
        'change select[data-choice-value-select]': '_onChangeChoiceValue'
    },

    _onChangePriceValue(e) {
        if (e.currentTarget.value < 0) {
            e.currentTarget.value = 0;
        }
    },
});

export default PracticeFrontendProductPriceFilter;
