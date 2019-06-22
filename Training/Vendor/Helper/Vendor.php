<?php

namespace Training\Vendor\Helper;

/**
 * Helper for working with Vendor
 */
class Vendor extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Training\Vendor\Model\Vendor
     */
    protected $modelVendor;

    /**
     * Class constructor
     * 
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Training\Vendor\Model\VendorFactory $vendorFactory
     *
     * @return void
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Training\Vendor\Model\VendorFactory $vendorFactory
    ) {
        parent::__construct($context);

        $this->storeManager = $storeManager;
        $this->modelVendor = $vendorFactory;
    }

    /**
     * Select vendor by string IDs and return array with vendor name and logo relative path
     * 
     * @param string $vendorIds
     * 
     * @return array
     */
    public function getVendorLogoById($vendorIds)
    {
        // Create vendor model
        $modelVendor = $this->modelVendor->create();
        // Get collection
        $collection = $modelVendor->getCollection()
            ->addFieldToSelect(['logo', 'name'])
            ->addFieldToFilter('entity_id', ['in' => $vendorIds])
            ->load();

        // Vendor data
        $data = $collection->getData();

        // Get media link
        $url = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        // Set media link to logo path
        foreach ($data as &$value) {
            $value['logo'] = $url . $value['logo'];
        }

        return $data;
    }
}
