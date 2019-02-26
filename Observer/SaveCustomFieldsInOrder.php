<?php
/**
* *
*  @author DCKAP Team
*  @copyright Copyright (c) 2018 DCKAP (https://www.dckap.com)
*  @package Dckap_CustomFields
*/

namespace Sendit\Bliskapaczka\Observer;

/**
* Class SaveCustomFieldsInOrder
* @package Dckap\CustomFields\Observer
*/
class SaveCustomFieldsInOrder implements \Magento\Framework\Event\ObserverInterface
{
   /**
    * @param \Magento\Framework\Event\Observer $observer
    * @return $this
    */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $quote = $observer->getEvent()->getQuote();

        $order->setData("pos_operator", $quote->getPosOperator());
        $order->setData("pos_code", $quote->getPosCode());
        $order->setData("pos_code_description", $quote->getPosCodeDescription());

        if (empty($quote->getPosOperator()) || empty($quote->getPosCode())) {
            return $this;
        }

        return $this;
    }
}
