<?php

namespace Training\Vendor\Controller\Adminhtml\Index;

/**
 * Controller for edit item
 */
class Edit extends \Magento\Backend\App\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var \Training\Vendor\Model\Vendor
     */
    protected $modelVendor;

    /**
     * Class constructor
     * 
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Training\Vendor\Model\Vendor $modelVendor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Training\Vendor\Model\Vendor $modelVendor
    ) {
        parent::__construct($context);

        $this->_resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->modelVendor = $modelVendor;
    }

    /**
     * Check permission for passed action
     * 
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Training_Vendor::main_index_edit');
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        // load layout, set active menu and breadcrumbs
        $resultPage->setActiveMenu('Training_Vendor::index')
            ->addBreadcrumb(__('Vendor'), __('Vendor'))
            ->addBreadcrumb(__('Manage Vendors'), __('Manage Vendors'));

        return $resultPage;
    }

    /**
     * Edit Vendor
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->modelVendor;

        // If you have got an id, it's edition
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                // Add error message
                $this->messageManager->addError(__('This vendor not exists.'));
                // Redirect to default page
                return $this->resultRedirectFactory->create()->setPath('*/*/');
            }
        }

        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('vendor_index', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Vendor') : __('New Vendor'),
            $id ? __('Edit Vendor') : __('New Vendor')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Vendors'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getName() : __('New Vendor'));

        return $resultPage;
    }
}
