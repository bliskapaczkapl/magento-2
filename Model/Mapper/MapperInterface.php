<?php

namespace Sendit\Bliskapaczka\Model\Mapper;

/**
 * Abstract class mappers
 */
interface MapperInterface
{
    /**
     * Prepare mapped data for Bliskapaczka API
     *
     * @param Mage_Sales_Model_Order $order
     * @param Sendit_Bliskapaczka_Helper_Data $helper
     * @return array
     */
    public function getData($order);
}
