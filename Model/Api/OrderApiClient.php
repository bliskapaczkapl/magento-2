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
        $this->validateResponse($response);
        return $response;

    }

    /**
     * @param string $id
     */
    public function setOrderId(string $id)
    {
        $this->apiClient->setOrderId($id);
    }

    /**
     * @return mixed
     * @throws \Sendit\Bliskapaczka\ApiClient\Exception
     */
    public function get()
    {
        $response = json_decode($this->apiClient->get());
        $this->validateResponse($response);
        return $response;
    }

    /**
     * @param mixed $response
     * @throws \Exception
     */
    protected function validateResponse($response)
    {
        if (isset($response->error)) {
            throw new \Exception($response->errors[0]->message);
        }
    }
}