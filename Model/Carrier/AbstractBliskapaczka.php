<?php

namespace Sendit\Bliskapaczka\Model\Carrier;

/**
 * Abstract class for bliskapaczka shippine method carrier
 */
abstract class AbstractBliskapaczka extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements
    \Magento\Shipping\Model\Carrier\CarrierInterface
{
    const COD = 'COD';

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

    /**
     * Set shipping method for operator
     *
     * @param Mage_Shipping_Model_Rate_Result_Method $result
     * @param string $operator
     * @param bool $cod
     * @param Sendit_Bliskapaczka_Helper_Data $senditHelper
     * @param float $shippingPrice
     */
    protected function _addShippingMethod($result, $operator, $cod, $senditHelper, $shippingPrice)
    {
        if ($this->_code != $operator->operatorName) {
            $methodName = $methodTitle = $operator->operatorName;
        } else {
            $methodName = $this->_code;
            $methodTitle = '';
        }

        if ($cod) {
            $methodName .= '_' . $this::COD;
            $methodTitle .= (($methodTitle) ? ' - ' : '') . 'Cash on Delivery';
        }

        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method = $this->_rateMethodFactory->create();

        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod($methodName);
        $method->setMethodTitle($methodTitle);

        $method->setPrice($shippingPrice);
        $method->setCost($shippingPrice);

        $result->append($method);
    }
}
