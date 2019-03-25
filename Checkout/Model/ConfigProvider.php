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
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * Shipping constructor.
     *
     * @param SenditHelper $senditHelper
     * @param \Magento\Checkout\Model\Session $_checkoutSession
     */
    public function __construct(
        SenditHelper $senditHelper,
        \Magento\Checkout\Model\Session $_checkoutSession
    ) {
        $this->senditHelper = $senditHelper;
        $this->_checkoutSession = $_checkoutSession;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $isFreeShipping = false;
        if ($this->_checkoutSession->getQuote()->getShippingAddress()->getFreeShipping() == 1) {
            $isFreeShipping = true;
        }
        $config = [];
        $config['operatorsForWidget'] = $this->senditHelper->getOperatorsForWidget(
            null,
            null,
            $isFreeShipping
        );
        $config['operatorsForWidgetCod'] = $this->senditHelper->getOperatorsForWidget(
            null,
            true,
            $isFreeShipping
        );

        return $config;
    }
}
