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

/**
 * Class InstallSchema
 * @package Dckap\CustomFields\Setup
 */
class InstallSchema implements InstallSchemaInterface
{

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @param Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param Magento\Framework\Setup\ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /* While module install, creates columns in quote_address and sales_order_address table */

        $eavTable1 = $installer->getTable('quote');
        $eavTable2 = $installer->getTable('sales_order');

        $columns = [
            'number' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'Number field',
            ],

            'status' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'Status field',
            ],

            'delivery_type' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'Delivery type field',
            ],

            'tracking_number' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'Tracking number field',
            ],

            'advice_date' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                'nullable' => true,
                'comment' => 'Advice date field',
            ],
            'pos_code' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'Pos code field',
            ],
            'pos_operator' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'Pos operator field',
            ],
            'pos_code_description' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'Pos code description type field',
            ],
            'bliskapaczka_status' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'Order status for bliskapaczka',
            ],
            'error_message' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'Order error for bliskapaczka',
            ],
            'attempts_count' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'nullable' => true,
                'comment' => 'Number attempts for bliskapaczka',
            ],
            'is_error' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                'nullable' => true,
                'comment' => 'Is error for bliskapaczka',
            ]
        ];

        $connection = $installer->getConnection();
        foreach ($columns as $name => $definition) {
            $connection->addColumn($eavTable1, $name, $definition);
            $connection->addColumn($eavTable2, $name, $definition);
        }
        $installer->endSetup();
    }
}
