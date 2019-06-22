<?php

namespace Training\Vendor\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Catalog\Model\Product;

/**
 * Setup - Upgrade data in tables
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $_eavSetupFactory;

    /**
     * Class constructor
     * 
     * @param \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     */
	public function __construct(EavSetupFactory $eavSetupFactory)
	{
		$this->_eavSetupFactory = $eavSetupFactory;
    }

    /**
     * Upgrade table data
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();

        $version = $context->getVersion();

        // Upgrade data to 1.0.1 version
        if (version_compare($version, '1.0.1') < 0) {
            $this->upgradeData1($setup);
        }

        $setup->endSetup();
    }

    /**
     * Upgrade data to 1.0.1 version
     * @param SchemaSetupInterface $setup
     */
    private function upgradeData1(&$setup)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->_eavSetupFactory->create(['setup' => $setup]);

        // Remove "select" attribute
        $eavSetup->removeAttribute(Product::ENTITY, 'vendor_id');

        // Add "multiselect" attribute for product
        $eavSetup->addAttribute(
            Product::ENTITY,
            'vendor_id',
            [
                'type' => 'text',
                'label' => 'Vendors',
                'input' => 'multiselect',
                'backend' => \Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend::class,
                'source' => \Training\Vendor\Model\Entity\Attribute\Source\Options::class,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'visible_on_front' => false
            ]
        );
    }
}
