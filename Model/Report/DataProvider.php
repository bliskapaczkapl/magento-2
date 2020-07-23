<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 22.01.19
 * Time: 09:14
 */

namespace Sendit\Bliskapaczka\Model\Report;

/**
 * Class DataProvider
 * @package Sendit\Bliskapaczka\Model\Report
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
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
