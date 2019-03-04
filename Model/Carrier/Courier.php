<?php

namespace Sendit\Bliskapaczka\Model\Carrier;

use Sendit\Bliskapaczka\Model\Api\Configuration;
use Sendit\Bliskapaczka\Model\Carrier\AbstractBliskapaczka;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Rate\Result;
use Sendit\Bliskapaczka\Helper\Data as SenditHelper;

/**
 * Courier class for bliskapaczka shippine method carrier
 */
class Courier extends AbstractBliskapaczka implements BliskapaczkaInterface
{
    /**
     * @var string
     */
    protected $_code = 'courier';

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
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    /**
     * get allowed methods
     * @return array
     */
    public function getAllowedMethods()
    {
        return [$this->_code => $this->getConfigData('name')];
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
        $configuration = Configuration::fromStoreConfiguration();

        $D2DData = array(
            "parcel" => array('dimensions' => $this->senditHelper->getParcelDimensions($type)),
            "deliveryType" => "D2D"
        );
        if ($cod) {
            $D2DData['codValue'] = 1;
        }

        $P2DData = array(
            "parcel" => array('dimensions' => $this->senditHelper->getParcelDimensions($type)),
            "deliveryType" => "P2D"
        );
        if ($cod) {
            $P2DData['codValue'] = 1;
        }

        try {
            /* @var $apiClient \Bliskapaczka\ApiClient\Bliskapaczka\Pricing */
            $apiClient = new \Bliskapaczka\ApiClient\Bliskapaczka\Pricing(
                $configuration->getApikey(),
                $configuration->getEnvironment()
            );

            $D2DPriceList = $apiClient->get($D2DData);
        } catch (\Bliskapaczka\ApiClient\Exception $e) {
            $D2DPriceList = $P2DPriceList = '{}';
            // $this->logger->info($e->getMessage());
            // Mage::log($e->getMessage(), null, Sendit_Bliskapaczka_Helper_Data::LOG_FILE);
        }

        return json_decode($D2DPriceList);
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

        $priceList = $this->_getPricing();

        if (!empty($priceList)) {
            foreach ($priceList as $operator) {
                if ($operator->availabilityStatus != false) {
                    $this->_addShippingMethod($result, $operator, false, $this->senditHelper, $operator->price->gross);
                }
            }
        }

        return $result;
    }
}
