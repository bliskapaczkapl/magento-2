<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 21.09.18
 * Time: 11:42
 */

namespace Sendit\Bliskapaczka\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        // Get tutorial_simplenews table
        $tableName = $installer->getTable('sendit_bliskapaczka_order');
        // Check if the table already exists
        if ($installer->getConnection()->isTableExists($tableName) != true) {
            // Create tutorial_simplenews table
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'entity_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'Entity Id'
                )
                ->addColumn(
                    'order_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false, 'unsigned' => true],
                    'Order Id'
                )
                ->addColumn(
                    'number',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => true, 'default' => ''],
                    'Number'
                )
                ->addColumn(
                    'status',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => true, 'default' => ''],
                    'Status'
                )
                ->addColumn(
                    'delivery_type',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => true],
                    'Delivery type'
                )
                ->addColumn(
                    'tracking_number',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => true, 'default' => ''],
                    'Tracking number'
                )
                ->addColumn(
                    'advice_date',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => true],
                    'Advice data'
                )
                ->addColumn(
                    'creation_date',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => true],
                    'Creation date'
                )
                ->addForeignKey(
                    $installer->getFkName('sendit_bliskapaczka/order', 'order_id', 'sales/order', 'entity_id'),
                    'order_id',
                    $installer->getTable('sales/order'),
                    'entity_id',
                    Table::ACTION_CASCADE,
                    Table::ACTION_CASCADE
                )
                ->setComment('News Table')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}