<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 12.12.18
 * Time: 13:45
 */

namespace Sendit\Bliskapaczka\Model\Api;

use Bliskapaczka\ApiClient\Bliskapaczka\Order;

class CancelApiClient
{
    /** @var Order\Cancel */
    protected $apiClient;
    private function __construct()
    {
    }
    public static function fromConfiguration(Configuration $configuration)
    {
        $apiClient = new CancelApiClient();
        $apiClient->apiClient = new Order\Cancel(
            $configuration->getApikey(),
            $configuration->getEnvironment()
        );
        return $apiClient;
    }

    /**
     * @return \Sendit\Bliskapaczka\ApiClient\Bliskapaczka\json
     * @throws \Exception
     */
    public function cancel()
    {
        $response = json_decode($this->apiClient->cancel());
        if (isset($response->errors)) {
            throw new \Exception($response->errors[0]->message);
        }
        return $response;
    }

    /**
     * @param string $orderId
     */
    public function setOrderId(string $orderId)
    {
        $this->apiClient->setOrderId($orderId);
    }
}
