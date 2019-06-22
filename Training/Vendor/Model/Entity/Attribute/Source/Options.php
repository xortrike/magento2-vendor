<?php

namespace Training\Vendor\Model\Entity\Attribute\Source;

/**
 * Source multiselect options
 */
class Options extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * Class constructor
     * 
     * @param \Training\Vendor\Model\Vendor $vendorModel
     */
    public function __construct(
        \Training\Vendor\Model\Vendor $vendorModel
    ) {
        $this->modelVendor = $vendorModel;
    }

    /**
    * Get all options
    *
    * @return array
    */
    public function getAllOptions()
    {
        $collection = $this->modelVendor->getCollection();
        $vendors = $collection->addFieldToSelect(['entity_id', 'name'])->getData();

        foreach ($vendors as $vendor) {
            $this->_options[] = [
                'label' => $vendor['name'],
                'value' => $vendor['entity_id']
            ];
        }

        return $this->_options;
    }
}
