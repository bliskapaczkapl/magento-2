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
     * @param Magento\Sales\Model\Order $order
     * @return array
     */
    public function getData($order);
}
