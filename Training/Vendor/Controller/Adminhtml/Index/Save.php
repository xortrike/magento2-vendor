<?php

namespace Training\Vendor\Controller\Adminhtml\Index;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Magento\Backend\App\Action
{
    protected $model;
    protected $uploaderFactory;
    protected $filesystem;

    /**
     * @param Action\Context $context
     * @param \Training\Vendor\Model\Vendor $model
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Training\Vendor\Model\Vendor $modelVendor,
        \Magento\MediaStorage\Model\File\UploaderFactory $UploaderFactory,
        \Magento\Framework\Filesystem $filesystem
    ) {
        parent::__construct($context);

        $this->model = $modelVendor;
        $this->uploaderFactory = $UploaderFactory;
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritdoc}
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
            $this->model->load($id);
        }

        // Save or delete logo
        if (isset($data['logo']['delete']) && $data['logo']['delete']) {
            $data['logo'] = '';
        } else if (isset($data['logo']['value'])) {
            $data['logo'] = $this->uploadFile('logo', $data['logo']['value']);
        } else {
            $data['logo'] = $this->uploadFile('logo', $this->model->getLogo());
        }

        $this->model->setData($data);

        try {
            $this->model->save();
            $this->messageManager->addSuccess(__('Vendor saved'));
            $this->_getSession()->setFormData(false);

            // Save and continue edit
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $this->model->getId(), '_current' => true]);
            }

            // Save
            return $resultRedirect->setPath('*/*/');
        } catch (LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        }

        $this->_getSession()->setFormData($data);

        return $resultRedirect->setPath('*/*/edit', ['entity_id' => $id]);
    }

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
            //
        } catch (\Exception $e) {
            //
        }

        return $uploadedFile;
    }
}
