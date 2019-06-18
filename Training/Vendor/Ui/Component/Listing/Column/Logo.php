<?php

namespace Training\Vendor\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Training\Vendor\Model\Vendor;

class Logo extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $modelProduct;

    /**
     * @var Training\Vendor\Model\Vendor
     */
    protected $modelVendor;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param ProductRepositoryInterface $ProductRepository
     * @param Vendor $VendorModel
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        StoreManagerInterface $storeManager,
        UrlInterface $urlBuilder,
        ProductRepositoryInterface $productRepository,
        Vendor $VendorModel,
        array $components = [],
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->urlBuilder = $urlBuilder;
        $this->modelProduct = $productRepository;
        $this->modelVendor = $VendorModel;

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items']) === false) {
            return $dataSource;
        }

        $fieldName = $this->getData('name');

        foreach ($dataSource['data']['items'] as &$item) {
            // Load product data
            $productData = $this->modelProduct->getById($item['entity_id']);
            // Get product vendor
            $vendorId = $productData->getVendorId();
            // Load vendor data
            $vendorData = $this->modelVendor->load($vendorId);

            // Media url
            $url = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
            $logo = $url . $vendorData->getLogo();
            // Link to item
            $link = $this->urlBuilder->getUrl('catalog/product/edit', ['id' => $item['entity_id']]);
            // Set Thumbnail
            $item[$fieldName . '_src'] = $logo;
            $item[$fieldName . '_alt'] = '';
            $item[$fieldName . '_link'] = $link;
            $item[$fieldName . '_orig_src'] = $logo;
        }

        return $dataSource;
    }
}
