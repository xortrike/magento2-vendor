<?php

namespace Training\Vendor\Model;

class Vendor extends \Magento\Framework\Model\AbstractModel
{
    const VENDOR_ID = 'entity_id'; // We define the id fieldname

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'vendor'; // parent value is 'core_abstract'

    /**
     * Name of the event object
     *
     * @var string
     */
    protected $_eventObject = 'vendor'; // parent value is 'object'

    /**
     * Name of object id field
     *
     * @var string
     */
    protected $_idFieldName = self::VENDOR_ID; // parent value is 'id'

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
