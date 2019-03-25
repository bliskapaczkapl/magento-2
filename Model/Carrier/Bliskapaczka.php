<?php

namespace Sendit\Bliskapaczka\Model\Carrier;

use Sendit\Bliskapaczka\Model\Api\Configuration;
use Sendit\Bliskapaczka\Model\Carrier\AbstractBliskapaczka;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Rate\Result;
use Sendit\Bliskapaczka\Helper\Data;
use Sendit\Bliskapaczka\Helper\Data as SenditHelper;

/**
 * P2P class for bliskapaczka shippine method carrier
 */
class Bliskapaczka extends AbstractBliskapaczka implements BliskapaczkaInterface
{
    /**
     * @var string
     */
    protected $_code = 'bliskapaczka';

    /**
     * Shipping constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface          $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory  $rateErrorFactory
     * @param \Psr\Log\LoggerInterface                                    $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory                  $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param \Sendit\Bliskapaczka\Helper\Data                            $senditHelper
     * @param array                                                       $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        SenditHelper $senditHelper,
        array $data = []
    ) {
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        $this->senditHelper = $senditHelper;
        $this->configuration = Configuration::fromStoreConfiguration();
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    /**
     * Get price list for carrier
     *
     * @param boot $cod
     * @param string $type
     *
     * @return array
     */
    public function _getPricing($cod = null, $type = 'fixed')
    {
        $priceList = $this->senditHelper->getPriceList($cod, $type);

        return $priceList;
    }

    /**
     * Collect rates
     *
     * @param \Magento\Quote\Model\Quote\Address\RateRequest $request
     * @return \Magento\Shipping\Model\Rate\Result $result
     */
    public function collectRates(RateRequest $request)
    {
        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->_rateResultFactory->create();

        foreach ([false, true] as $cod) {
            if (!$this->configuration->cod && $cod == true) {
                break;
            }

            $priceList = $this->_getPricing();

            if (!empty($priceList)) {
                $price = $this->senditHelper->getLowestPrice($priceList);

                $operator = new \StdClass();
                $operator->operatorName = $this->_code;
                $operator->operatorFullName = $this->_code;
                if ($request->getFreeShipping() === true) {
                    $price = 0;
                }
                $this->_addShippingMethod($result, $operator, $cod, $this->senditHelper, $price);
            }
        }

        return $result;
    }
}
