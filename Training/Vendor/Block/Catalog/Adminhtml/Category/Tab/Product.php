<?php

namespace Training\Vendor\Block\Catalog\Adminhtml\Category\Tab;

/**
 * Block product tab
 */
class Product extends \Magento\Catalog\Block\Adminhtml\Category\Tab\Product
{
    /**
     * Mark custom fields only once
     * 
     * @var bool
     */
    private $customFields = false;

    /**
     * Apply filter for custom fields
     * 
     * @param \Magento\Backend\Block\Widget\Grid\Column $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Add custom fiels
        $this->joinCustomFields();

        // Field index
        $field = $column->getId();

        if ($field == 'vendor_name' || $field == 'vendor_description') {
            $value = '%'.$column->getFilter()->getValue().'%';
            $this->getCollection()->addFieldToFilter($field, ['like' => $value]);
        } else {
            parent::_addColumnFilterToCollection($column);
        }

        return $this;
    }

    /**
     * Join custom fields
     * 
     * @return \Magento\Backend\Block\Widget\Grid
     */
    protected function _prepareCollection()
    {
        $grid = parent::_prepareCollection();

        // Join custom fiels
        $this->joinCustomFields();

        if ($this->getCollection()->isLoaded()) {
            $this->getCollection()->clear();
        }

        return $grid;
    }

    /**
     * Add column to grid table
     * 
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareColumns()
    {
        // Add column vendor name after sku
        $this->addColumnAfter('vendor_name', [
            'header' => __('Vendor&#160;Name'),
            'index' => 'vendor_name',
        ], 'sku');

        // Add column vendor description after vendor description
        $this->addColumnAfter('vendor_description', [
            'header' => __('Vendor&#160;Description'),
            'index' => 'vendor_description',
        ], 'vendor_name');

        return parent::_prepareColumns();
    }

    /**
     * Join custom fields
     * 
     * @return void
     */
    private function joinCustomFields()
    {
        if ($this->customFields === false) {

            $collection = $this->getCollection();

            // Join attribute vendor_id
            $collection->joinAttribute(
                'vendor_id',
                'catalog_product/vendor_id',
                'entity_id',
                null,
                'left'
            );

            // Join vendor name and description
            $collection->joinTable(
                'training_vendor',
                'entity_id = vendor_id',
                [
                    'vendor_name' => 'name',
                    'vendor_description' => 'description'
                ],
                null,
                'left'
            );

            $this->setCollection($collection);

            $this->customFields = true;
        }
    }
}
