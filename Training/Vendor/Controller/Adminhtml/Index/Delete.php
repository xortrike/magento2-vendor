<?php

namespace Training\Vendor\Controller\Adminhtml\Index;

class Delete extends \Magento\Backend\App\Action
{
    protected $_model;

    /**
     * @param Action\Context $context
     * @param \Training\Vendor\Model\Vendor $model
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Training\Vendor\Model\Vendor $model
    ) {
        parent::__construct($context);
        $this->_model = $model;
    }

    /**
     * {@inheritdoc}
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
        $id = $this->getRequest()->getParam('id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->_model;
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('Vendor deleted'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__('Vendor does not exist'));
        return $resultRedirect->setPath('*/*/');
    }
}
