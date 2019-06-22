<?php

namespace Training\Vendor\Controller\Adminhtml\Index;

/**
 * Controller for delete item
 */
class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var \Training\Vendor\Model\Vendor
     */
    protected $modelVendor;

    /**
     * Class constructor
     * 
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Training\Vendor\Model\Vendor $modelVendor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Training\Vendor\Model\Vendor $modelVendor
    ) {
        parent::__construct($context);

        $this->modelVendor = $modelVendor;
    }

    /**
     * Check permission for passed action
     * 
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Training_Vendor::main_index_delete');
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // Get item ID
        $id = $this->getRequest()->getParam('id');

        if ($id) {
            // Add error message
            $this->messageManager->addError(__('Vendor does not exist'));
            // Redirect to default page
            return $resultRedirect->setPath('*/*/');
        }

        try {
            // Delete item by ID
            $this->modelVendor->load($id)->delete();
            // Add success message about deleted item
            $this->messageManager->addSuccess(__('Vendor deleted'));
            // Redirect to default page
            return $resultRedirect->setPath('*/*/');
        } catch (\Exception $e) {
            // Add error message
            $this->messageManager->addError($e->getMessage());
            // Redirect to edit page
            return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
        }
    }
}
