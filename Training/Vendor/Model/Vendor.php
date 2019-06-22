<?php

namespace Training\Vendor\Model;

/**
 * Model Vendor
 */
class Vendor extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var string Fieldname ID
     */
    const VENDOR_ID = 'entity_id';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'vendor';

    /**
     * Name of the event object
     *
     * @var string
     */
    protected $_eventObject = 'vendor';

    /**
     * Name of object id field
     *
     * @var string
     */
    protected $_idFieldName = self::VENDOR_ID;

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Training\Vendor\Model\ResourceModel\Vendor::class);
    }
}
