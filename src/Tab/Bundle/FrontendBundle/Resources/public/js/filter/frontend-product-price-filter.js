import BaseFilter from 'oro/filter/frontend-product-price-filter';
import _ from 'underscore';
import $ from 'jquery';

const FrontendProductPriceFilter = BaseFilter.extend({

    events: {
        'input input.input-widget': 'normalizeNumberFieldValue',
        'change input.input-widget': 'normalizeNumberFieldValue',
    },

    normalizeNumberFieldValue({target}) {
        if (!isNaN(target.value)) {
            target.value = Math.abs(target.value);
        }
    },
});

export default FrontendProductPriceFilter;