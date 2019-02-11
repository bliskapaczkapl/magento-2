<?php
/**
* *
*  @author DCKAP Team
*  @copyright Copyright (c) 2018 DCKAP (https://www.dckap.com)
*  @package Dckap_CustomFields
*/

namespace Sendit\Bliskapaczka\Observer;

use Sendit\Bliskapaczka\Model\Api\Configuration;
use Sendit\Bliskapaczka\Model\Api\OrderApiClient;
use Sendit\Bliskapaczka\Model\Api\ReceiverConfiguration;
use Sendit\Bliskapaczka\Model\Api\SenderConfiguration;

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
        if (empty($quote->getPosOperator()) || empty($quote->getPosCode())) {
              return $this;
        }
        $conf = Configuration::fromStoreConfiguration();
        $orderApiClient = OrderApiClient::fromConfiguration($conf);
        $senderConfiguration = SenderConfiguration::fromStoreConfiguration();
        $receiverConfiguration = ReceiverConfiguration::fromQuote($quote);
        $senderConfArray = $senderConfiguration->toArray();
        $receiverConfigurationArray = $receiverConfiguration->toArray();
        $data = array_merge(
            $senderConfArray,
            $receiverConfigurationArray,
            [
            "postingCode" => "WRO206",
            "deliveryType" => "P2P"
            ]
        );
        // $resp = $orderApiClient->create($data);
        // $resp = json_decode($resp);
        // $order->setData("pos_operator", $quote->getPosOperator());
        // $order->setData("pos_code", $quote->getPosCode());
        // $order->setData("pos_code_description", $quote->getPosCodeDescription());
        // $order->setData("number", $resp->number);
        // $order->setData("delivery_type", $resp->deliveryType);
        // $order->setData("tracking_number", $resp->trackingNumber);
        // $order->setData("advice_date", $resp->adviceDate);


        return $this;
    }
}
