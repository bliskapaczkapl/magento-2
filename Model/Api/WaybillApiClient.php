<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 12.12.18
 * Time: 13:45
 */

namespace Sendit\Bliskapaczka\Model\Api;

use Bliskapaczka\ApiClient\Bliskapaczka\Order;
class WaybillApiClient
{
    /** @var Order */
    protected $apiClient;
    private function __construct(){}
    public static function fromConfiguration(Configuration $configuration)
    {
        $apiClient = new WaybillApiClient;
        $apiClient->apiClient = new Order\Waybill(
            $configuration->getApikey(),
            $configuration->getEnvironment()
        );
        return $apiClient;
    }

    /**
     * @return \Sendit\Bliskapaczka\ApiClient\Bliskapaczka\json
     * @throws \Bliskapaczka\ApiClient\Exception
     */
    public function get()
    {
        $response = json_decode($this->apiClient->get());
        if (isset($response->errors)) {
            throw new \Exception($response->errors[0]->message);
        }

       return $response;
    }

    /**
     * @param $orderId
     */
    public function setOrderId($orderId)
    {
        $this->apiClient->setOrderId($orderId);
    }
}