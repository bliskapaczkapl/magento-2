<?php

namespace Sendit\Bliskapaczka\ApiClient\Bliskapaczka\Pricing;

use Sendit\Bliskapaczka\ApiClient\Bliskapaczka\Pricing;
use Sendit\Bliskapaczka\ApiClient\BliskapaczkaInterface;

/**
 * Bliskapaczka class
 *
 * @author  Mateusz Koszutowski (mkoszutowski@divante.pl)
 * @version 0.1.0
 */
class Todoor extends Pricing implements BliskapaczkaInterface
{
    const REQUEST_URL = 'pricing/todoor';
}
