<?php

namespace Training\Vendor\Setup;

use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class Uninstall implements UninstallInterface
{
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $version = $context->getVersion();

        $connection = $setup->getConnection();

        // Table list for drop
        $tableList = [
            'training_vendor'
        ];

        foreach ($tableList as $table) {
            // Table name
            $tableName = $setup->getTable($table);
            // Drop table if exists
            if ($connection->isTableExists($tableName)) {
                $connection->dropTable($tableName);
            }
        }

        // Remove product attribute
        $this->removeProductAttribute($setup);

        $setup->endSetup();
    }

    private function removeProductAttribute(&$setup)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'vendor_id');
    }
}
