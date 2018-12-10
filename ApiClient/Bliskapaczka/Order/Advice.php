<?php

namespace Sendit\Bliskapaczka\ApiClient\Bliskapaczka\Order;

use Sendit\Bliskapaczka\ApiClient\Bliskapaczka\Order;
use Sendit\Bliskapaczka\ApiClient\BliskapaczkaInterface;
use Sendit\Bliskapaczka\ApiClient\AbstractBliskapaczka;

/**
 * Bliskapaczka class
 *
 * @author  Mateusz Koszutowski (mkoszutowski@divante.pl)
 * @version 0.1.0
 */
class Advice extends Order implements BliskapaczkaInterface
{
    const REQUEST_URL = 'order/advice';

    /**
     * Return end of url for request
     */
    public function getUrl()
    {
        return static::REQUEST_URL;
    }

    /**
     * Call API method create order
     *
     * @param array $data
     * @return json $response
     */
    public function create(array $data)
    {
        if (isset($this->orderId)) {
            $data['number'] = $this->orderId;
        }

        return parent::create($data);
    }
}
