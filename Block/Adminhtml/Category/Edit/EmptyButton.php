<?php

namespace Blackbird\CategoryEmptyButton\Block\Adminhtml\Category\Edit;

use Magento\Catalog\Block\Adminhtml\Category\AbstractCategory;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class DeleteButton
 */
class EmptyButton extends AbstractCategory implements ButtonProviderInterface
{
    /**
     * Delete button
     *
     * @return array
     */
    public function getButtonData()
    {
        $category = $this->getCategory();
        $categoryId = (int)$category->getId();

        if ($categoryId && $category->isDeleteable()) {
            return [
                'id' => 'empty_button',
                'label' => __('Empty Category Products Links'),
                'on_click' => "deleteConfirm('" . __('Are you sure you want to remove all the products from this category ?') . "', '"
                    . $this->getEmptyUrl() . "', {data: {}})",
                'class' => 'action-secondary',
                'sort_order' => 20
            ];
        }

        return [];
    }

    /**
     * @param array $args
     * @return string
     */
    public function getEmptyUrl(array $args = [])
    {
        $params = array_merge($this->getDefaultUrlParams(), $args);
        return $this->getUrl('categoryempty/*/setempty', $params);
    }

    /**
     * @return array
     */
    protected function getDefaultUrlParams()
    {
        return ['_current' => true, '_query' => ['isAjax' => null]];
    }
}
