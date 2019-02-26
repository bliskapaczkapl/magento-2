<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 22.01.19
 * Time: 09:14
 */

namespace Sendit\Bliskapaczka\Model\Report;

use Sendit\Bliskapaczka\Model\ResourceModel\Report\CollectionFactory;

/**
 * Class DataProvider
 * @package Sendit\Bliskapaczka\Model\Report
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * DataProvider constructor.
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param CollectionFactory $contactCollectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $contactCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $contactCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }


    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return [];
    }
}
