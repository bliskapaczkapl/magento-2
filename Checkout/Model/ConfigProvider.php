<?php
namespace Sendit\Bliskapaczka\Checkout\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Sendit\Bliskapaczka\Helper\Data as SenditHelper;

/**
 * Class ConfigProvider
 * @package Sendit\Bliskapaczka\Checkout\Model
 */
class ConfigProvider implements ConfigProviderInterface
{
    /**
     * Shipping constructor.
     *
     * @param SenditHelper $senditHelper
     */
    public function __construct(
        SenditHelper $senditHelper
    ) {
        $this->senditHelper = $senditHelper;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $config = [];
        $config['operatorsForWidget'] = $this->senditHelper->getOperatorsForWidget();
        $config['operatorsForWidgetCod'] = $this->senditHelper->getOperatorsForWidget(null, true);

        return $config;
    }
}
