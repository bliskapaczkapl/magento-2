<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 12.12.18
 * Time: 13:45
 */

namespace Sendit\Bliskapaczka\Model\Api;

use Sendit\Bliskapaczka\ApiClient\Bliskapaczka\Order;
class OrderApiClient
{
    /** @var Order */
    protected $apiClient;
    private function __construct(){}
    public static function fromConfiguration(Configuration $configuration)
    {
        $apiClient = new OrderApiClient;
        $apiClient->apiClient = new Order(
            $configuration->getApikey(),
            $configuration->getEnvironment()
        );
        return $apiClient->apiClient;
    }

    /**
     * @param array $data
     * @return \Sendit\Bliskapaczka\ApiClient\Bliskapaczka\json
     */
    public function create(array $data)
    {
       return $this->apiClient->create($data);
    }
}