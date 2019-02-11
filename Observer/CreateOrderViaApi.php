<?php

namespace Sendit\Bliskapaczka\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Sendit\Bliskapaczka\Model\Api\Configuration;
use Bliskapaczka\ApiClient\Bliskapaczka\Order as BliskapaczkaOrder;
use Bliskapaczka\ApiClient\Bliskapaczka\Todoor as BliskapaczkaTodoor;
use Bliskapaczka\ApiClient\Bliskapaczka\Todoor\Advice as BliskapaczkaTodoorAdvice;
use Sendit\Bliskapaczka\Model\Mapper\Order;
use Sendit\Bliskapaczka\Model\Mapper\Todoor;
use Sendit\Bliskapaczka\Helper\Data as SenditHelper;

class CreateOrderViaApi implements ObserverInterface
{
    public function __construct(
        Todoor $todoor
    ) {
        $this->todoor = $todoor;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $order = $observer->getEvent()->getOrder();

            $configuration = Configuration::fromStoreConfiguration();

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
            $data = $mapper->getData($order);

            $response = $apiClient->create($data);
        } catch (Exception $e) {
        }
    }
}
