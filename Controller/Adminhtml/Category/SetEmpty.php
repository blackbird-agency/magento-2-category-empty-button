<?php
/**
 * Blackbird Category Empty Button
 *
 * NOTICE OF LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@bird.eu so we can send you a copy immediately.
 *
 * @category        Blackbird
 * @package         Blackbird_CategoryEmptyButton
 * @copyright       Copyright (c) Blackbird (https://black.bird.eu)
 * @author          Blackbird Team
 * @license         MIT
 * @support         https://github.com/blackbird-agency/magento-2-category-empty-button/issues/new
 */
namespace Blackbird\CategoryEmptyButton\Controller\Adminhtml\Category;

use Magento\Framework\App\Action\HttpPostActionInterface;

class SetEmpty extends \Magento\Catalog\Controller\Adminhtml\Category implements HttpPostActionInterface
{
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resource;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\ResourceConnection $resource
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\ResourceConnection $resource
    ) {
        parent::__construct($context);
        $this->resource = $resource;
    }

    /**
     * Delete category action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $categoryId = (int)$this->getRequest()->getParam('id');
        if ($categoryId) {
            try {
                $this->_eventManager->dispatch('catalog_controller_category_empty', ['category_id' => $categoryId]);
                $connection = $this->resource->getConnection();
                $connection->delete($connection->getTableName("catalog_category_product"), "category_id=" . $categoryId);
                $this->messageManager->addSuccessMessage(__('You removed the products from the category.'));
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('catalog/*/edit', ['_current' => true]);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong while trying to remove the products from the category. ' . $e->getMessage()));
                return $resultRedirect->setPath('catalog/*/edit', ['_current' => true]);
            }
        }
        return $resultRedirect->setPath('catalog/*/edit', ['_current' => true, 'id' => $categoryId]);
    }
}
