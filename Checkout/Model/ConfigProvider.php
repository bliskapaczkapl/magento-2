<?php
namespace Sendit\Bliskapaczka\Checkout\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Sendit\Bliskapaczka\Helper\Data as SenditHelper;

class ConfigProvider implements ConfigProviderInterface
{
    /**
     * Shipping constructor.
     *
     * @param 
     */
    public function __construct(
        SenditHelper $senditHelper
    ) {
        $this->senditHelper = $senditHelper;
    }

    public function getConfig()
    {
        $config = [];
        $config['operatorsForWidget'] = $this->senditHelper->getOperatorsForWidget();

        return $config;
    }
}