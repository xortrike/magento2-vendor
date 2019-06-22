<?php

namespace Training\Vendor\Controller\Adminhtml\Index;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Controller save item
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Training\Vendor\Model\Vendor
     */
    protected $modelVendor;

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $uploaderFactory;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * Class constructor
     * 
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Training\Vendor\Model\Vendor $modelVendor
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $UploaderFactory
     * @param \Magento\Framework\Filesystem $filesystem
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Training\Vendor\Model\Vendor $modelVendor,
        \Magento\MediaStorage\Model\File\UploaderFactory $UploaderFactory,
        \Magento\Framework\Filesystem $filesystem
    ) {
        parent::__construct($context);

        $this->modelVendor = $modelVendor;
        $this->uploaderFactory = $UploaderFactory;
        $this->filesystem = $filesystem;
    }

    /**
     * Check permission for passed action
     * 
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Training_Vendor::index_save');
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $data = $this->getRequest()->getPostValue();

        // Save Data
        if (!$data) {
            return $resultRedirect->setPath('*/*/');
        }

        // Load item by ID
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            $this->modelVendor->load($id);
        }

        // Save or delete logo
        if (isset($data['logo']['delete']) && $data['logo']['delete']) {
            $data['logo'] = '';
        } else if (isset($data['logo']['value'])) {
            $data['logo'] = $this->uploadFile('logo', $data['logo']['value']);
        } else {
            $data['logo'] = $this->uploadFile('logo', $this->modelVendor->getLogo());
        }

        $this->modelVendor->setData($data);

        try {
            $this->modelVendor->save();
            $this->messageManager->addSuccess(__('Vendor saved'));
            $this->_getSession()->setFormData(false);

            // Save and continue edit
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $this->modelVendor->getId(), '_current' => true]);
            }

            // Save
            return $resultRedirect->setPath('*/*/');
        } catch (LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        }

        $this->_getSession()->setFormData($data);

        return $resultRedirect->setPath('*/*/edit', ['entity_id' => $id]);
    }

    /**
     * Upload file
     * 
     * @param string $scope Image param name
     * @param string $uploadedFile Upload file path
     * 
     * @return string
     */
    public function uploadFile($scope, $uploadedFile)
    {
        try {
            $uploader = $this->uploaderFactory->create(['fileId' => $scope]);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $uploader->setAllowCreateFolders(true);

            $myFolder = 'vendors';
            $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
            $destinationPath = $mediaDirectory->getAbsolutePath($myFolder);
            if ($uploader->save($destinationPath)) {
                return $myFolder.$uploader->getUploadedFileName();
            }
        } catch (LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        return $uploadedFile;
    }
}
