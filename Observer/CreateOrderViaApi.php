<?php

namespace Sendit\Bliskapaczka\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Sendit\Bliskapaczka\Model\Api\Configuration;
use Bliskapaczka\ApiClient\Bliskapaczka\Order as BliskapaczkaOrder;
use Bliskapaczka\ApiClient\Bliskapaczka\Todoor as BliskapaczkaTodoor;
use Bliskapaczka\ApiClient\Bliskapaczka\Order\Advice as BliskapaczkaOrderAdvice;
use Bliskapaczka\ApiClient\Bliskapaczka\Order\Todoor as BliskapaczkaOrderTodoor;
use Sendit\Bliskapaczka\Model\Mapper\Order;
use Sendit\Bliskapaczka\Model\Mapper\Todoor;
use Sendit\Bliskapaczka\Helper\Data as SenditHelper;

class CreateOrderViaApi implements ObserverInterface
{
    public function __construct(
        Todoor $todoor,
        Order $order
    ) {
        $this->todoor = $todoor;
        $this->order = $order;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $order = $observer->getEvent()->getOrder();

            $configuration = Configuration::fromStoreConfiguration();

            if ($order->getPosCode()) {
                $apiClient = new BliskapaczkaOrder(
                    $configuration->getApikey(),
                    $configuration->getEnvironment()
                );

                if ($configuration->autoAdvice) {
                    $apiClient = new BliskapaczkaOrderAdvice(
                        $configuration->getApikey(),
                        $configuration->getEnvironment()
                    );
                }

                $mapper = $this->order;
            } else {
                $apiClient = new BliskapaczkaTodoor(
                    $configuration->getApikey(),
                    $configuration->getEnvironment()
                );

                if ($configuration->autoAdvice) {
                    $apiClient = new BliskapaczkaTodoorAdvice(
                        $configuration->getApikey(),
                        $configuration->getEnvironment()
                    );
                }

                $mapper = $this->todoor;
            }

            $data = $mapper->getData($order);

            $response = $apiClient->create($data);
            $response = json_decode($response);
            $order->setData("number", $response->number);
            $order->setData("delivery_type", $response->deliveryType);
            $order->setData("tracking_number", $response->trackingNumber);
            $order->setData("advice_date", $response->adviceDate);
        } catch (Exception $e) {
        }
    }
}
