<?php

namespace Practice\Bundle\ProductBundle\Filter;

use Oro\Bundle\AttachmentBundle\Manager\AttachmentManager;
use Oro\Bundle\FilterBundle\Filter\FilterUtility;
use Oro\Bundle\SearchBundle\Datagrid\Filter\SearchEntityFilter;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;

class ProductBrandFilter extends SearchEntityFilter
{
    protected AttachmentManager $attachmentManager;

    public function setAttachmentManager(AttachmentManager $attachmentManager)
    {
        $this->attachmentManager = $attachmentManager;
    }

    public function getMetadata()
    {
        $formView = $this->getFormView();
        $fieldView = $formView->children['value'];

        $choices = array_map(
            function (ChoiceView $choice) {
                $imageUrl = $choice->data->getImage() ?
                    $this->attachmentManager->getFilteredImageUrl($choice->data->getImage(), 'product_small') : '';
                return [
                    'label' => $choice->label,
                    'value' => $choice->value,
                    'imageUrl' => $imageUrl,
                ];
            },
            $fieldView->vars['choices']
        );

        $metadata = parent::getMetadata();
        $metadata['choices'] = $choices;
        $metadata[FilterUtility::TYPE_KEY] = $this->get(FilterUtility::TYPE_KEY);
        return $metadata;
    }
}
