<?php

namespace Training\Vendor\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallData implements InstallDataInterface
{
    private $_eavSetupFactory;

	public function __construct(EavSetupFactory $eavSetupFactory)
	{
		$this->_eavSetupFactory = $eavSetupFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $version = $context->getVersion();

        // Add attribute to product
        $this->addProductAttribute($setup);

        $setup->endSetup();
    }

    private function addProductAttribute(&$setup)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->_eavSetupFactory->create(['setup' => $setup]);

        $attribute = $eavSetup->getAttribute(\Magento\Catalog\Model\Product::ENTITY, 'vendor_name');

        if (empty($attribute) === false) {
            return false;
        }

        /**
         * Add attributes to the eav/attribute
         */
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'vendor_id',
            [
                'type' => 'int',
                'backend' => '',
                'label' => 'Vendor Name',
                'input' => 'select',
                'source' => \Training\Vendor\Model\Entity\Attribute\Source\Options::class,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'visible_on_front' => false
            ]
        );
    }
}
