<?php

namespace Training\Vendor\Controller\Adminhtml\Index;

class Index extends \Magento\Backend\App\Action
{
    /**
     * Test authorization and access in this controller
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Training_Vendor::main_index');
    }

    /**
     * Execute action based
     * @return ResultFactory
     */
    public function execute()
    {
        return $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
    }
}
