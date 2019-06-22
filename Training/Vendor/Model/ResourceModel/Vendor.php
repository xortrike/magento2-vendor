<?php

namespace Training\Vendor\Model\ResourceModel;

/**
 * Resource model Vendor
 */
class Vendor extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        // Table Name and Primary Key column
        $this->_init('training_vendor', 'entity_id');
    }
}
