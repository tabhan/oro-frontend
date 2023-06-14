import FrontendMultiSelectFilter from 'orofrontend/default/js/app/views/frontend-multiselect-filter';
import MultiselectDecorator from 'practiceproduct/js/datafilter/customize-frontend-multiselect-decorator';


const ProductBrandListFilter = FrontendMultiSelectFilter.extend({

    MultiselectDecorator: MultiselectDecorator,

    template: require('tpl-loader!practiceproduct/templates/filter/product-brand-list-filter.html'),

    // getTemplateData: function() {
    //     const templateData = ProductBrandListFilter.__super__.getTemplateData.call(this);
    //
    //     return this.filterTemplateData(templateData);
    // },
    //
    // _onClickFilterArea: function(e) {
    //     e.stopPropagation();
    //
    //     if (this.isToggleMode()) {
    //         this.toggleFilter();
    //     } else {
    //         ProductBrandListFilter.__super__._onClickFilterArea.call(this, e);
    //         console.log('testing');
    //     }
    // },
    //
    // setDesignForCheckboxesDefaultTheme: function(instance) {
    //     instance.menu
    //         .children('.ui-multiselect-checkboxes')
    //         .find('li');
    // },

});

export default ProductBrandListFilter;
