<?php

namespace Training\Vendor\Model\ResourceModel\Vendor;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = \Training\Vendor\Model\Vendor::VENDOR_ID;

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Training\Vendor\Model\Vendor::class,
            \Training\Vendor\Model\ResourceModel\Vendor::class
        );
    }
}
