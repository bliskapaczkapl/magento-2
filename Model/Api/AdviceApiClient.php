<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 12.12.18
 * Time: 13:45
 */

namespace Sendit\Bliskapaczka\Model\Api;

use Sendit\Bliskapaczka\ApiClient\Bliskapaczka\Order;
class AdviceApiClient
{
    /** @var Order */
    protected $apiClient;
    private function __construct(){}
    public static function fromConfiguration(Configuration $configuration)
    {
        $apiClient = new AdviceApiClient;
        $apiClient->apiClient = new Order\Advice(
            $configuration->getApikey(),
            $configuration->getEnvironment()
        );
        return $apiClient;
    }

    /**
     * @param array $data
     * @return \Sendit\Bliskapaczka\ApiClient\Bliskapaczka\json
     * @throws \Exception
     */
    public function create(array $data)
    {
        $response = json_decode($this->apiClient->create($data));
        if (isset($response->errors)) {
            throw new \Exception($response->errors[0]->message);
        }
       return $this->apiClient->create($data);
    }

}