<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 21.11.18
 * Time: 07:32
 */

namespace Sendit\Bliskapaczka\Grid\Model\ResourceModel\Grid;

/**
 * Grid of bliskapaczka.pl on order page
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init('Sendit\Bliskapaczka\Grid\Model\Grid', 'Sendit\Bliskapaczka\Grid\Model\ResourceModel\Grid');
    }
}
