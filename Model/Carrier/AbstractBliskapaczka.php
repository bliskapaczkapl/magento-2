<?php

namespace Sendit\Bliskapaczka\Model\Carrier;

abstract class AbstractBliskapaczka extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements
    \Magento\Shipping\Model\Carrier\CarrierInterface
{
    /**
     * Get allowed shipping methods
     *
     * @return array
     */
    public function getAllowedMethods()
    {
        $priceList = $this->_getPricing(null, 'default');
        $allowedShippingMethod = array();
        foreach ($priceList as $operator) {
            $allowedShippingMethod[$operator->operatorName] = $operator->operatorFullName;
            $allowedShippingMethod[$operator->operatorName . '_COD'] = $operator->operatorFullName . ' COD';
        }

        $allowedShippingMethod['sendit_bliskapaczka'] = 'bliskapaczka.pl';

        return $allowedShippingMethod;
    }
}
