<?php

namespace Training\Vendor\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;

class Thumbnail extends \Magento\Ui\Component\Listing\Columns\Column
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
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        StoreManagerInterface $storeManager,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->urlBuilder = $urlBuilder;

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
            // Media url
            $url = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
            $logo = $url . $item['logo'];
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
