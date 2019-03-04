<?php

namespace Sendit\Bliskapaczka\Model\Carrier;

/**
 * Abstract class mappers
 */
interface BliskapaczkaInterface
{
    /**
     * Get price list for carrier
     *
     * @param boot $cod
     * @param string $type
     *
     * @return json
     */
    public function _getPricing($cod = null, $type = 'fixed');
}
