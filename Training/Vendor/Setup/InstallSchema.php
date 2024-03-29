<?php

namespace Training\Vendor\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * Setup - Install tables schema
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * Install
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        // Create table schema - Vendor
        $this->setVendorSchema($setup);

        $setup->endSetup();
    }

    /**
     * Table Schema - Vendor
     * 
     * @param SchemaSetupInterface $setup
     */
    private function setVendorSchema(&$setup)
    {
        // Table name
        $tableName = $setup->getTable('training_vendor');

        $connection = $setup->getConnection();

        // Drop table if exists
        if ($connection->isTableExists($tableName)) {
            $connection->dropTable($tableName);
        }

        // Create table object
        $table = $connection->newTable($tableName);
        // Set table information and options
        $table->setComment('Training Vendor - Vendors');
        $table->setOption('type', 'InnoDB');
        $table->setOption('charset', 'utf8');

        // Table columns
        $tableColumns = [
            [
                'name' => 'entity_id',
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'comment' => 'Vendor index'
            ],
            [
                'name' => 'name',
                'type' => Table::TYPE_TEXT,
                'size' => null,
                'options' => ['nullable' => false],
                'comment' => 'Vendor name'
            ],
            [
                'name' => 'description',
                'type' => Table::TYPE_TEXT,
                'size' => null,
                'options' => ['nullable' => false],
                'comment' => 'Vendor description'
            ],
            [
                'name' => 'logo',
                'type' => Table::TYPE_TEXT,
                'size' => null,
                'options' => ['nullable' => false],
                'comment' => 'Logo'
            ],
            [
                'name' => 'created_at',
                'type' => Table::TYPE_TIMESTAMP,
                'size' => null,
                'options' => ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'comment' => 'Date Added'
            ]
        ];

        // Create table schema
        foreach ($tableColumns as $column) {
            $table->addColumn(
                $column['name'],
                $column['type'],
                $column['size'],
                $column['options'],
                $column['comment']
            );
        }

        $connection->createTable($table);
    }
}
