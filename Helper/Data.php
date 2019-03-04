<?php

namespace Sendit\Bliskapaczka\Helper;

use \Sendit\Bliskapaczka\Model\Api\Configuration;
use \Bliskapaczka\ApiClient\Pricing;
use \Magento\Framework\App\Helper\AbstractHelper;

/**
 * Bliskapaczka helper
 *
 * @author Mateusz Koszutowski (mkoszutowski@divante.pl)
 */
class Data extends AbstractHelper
{
    const DEFAULT_GOOGLE_API_KEY = 'AIzaSyCUyydNCGhxGi5GIt5z5I-X6hofzptsRjE';

    const PARCEL_DEFAULT_SIZE_X = 12;
    const PARCEL_DEFAULT_SIZE_Y = 12;
    const PARCEL_DEFAULT_SIZE_Z = 16;
    const PARCEL_DEFAULT_SIZE_WEIGHT = 1;

    const SLOW_STATUSES = array('READY_TO_SEND', 'POSTED', 'ON_THE_WAY', 'READY_TO_PICKUP', 'OUT_FOR_DELIVERY',
            'REMINDER_SENT', 'PICKUP_EXPIRED', 'AVIZO', 'RETURNED', 'OTHER', 'MARKED_FOR_CANCELLATION');
    const FAST_STATUSES = array('SAVED', 'WAITING_FOR_PAYMENT', 'PAYMENT_CONFIRMED', 'PAYMENT_REJECTED',
            'PAYMENT_CANCELLATION_ERROR', 'PROCESSING', 'ADVISING', 'ERROR');

    const LOG_FILE = 'sendit.log';

    /**
     * Construct method
     */
    public function __construct()
    {
        $this->configuration = Configuration::fromStoreConfiguration();
    }

    /**
     * Get parcel dimensions in format accptable by Bliskapaczka API
     *
     * @param string $type
     * @return array
     */
    public function getParcelDimensions($type = 'fixed')
    {
        switch ($type) {
            case 'default':
                $height = self::PARCEL_DEFAULT_SIZE_X;
                $length = self::PARCEL_DEFAULT_SIZE_Y;
                $width = self::PARCEL_DEFAULT_SIZE_Z;
                $weight = self::PARCEL_DEFAULT_SIZE_WEIGHT;
                break;

            default:
                $height = $this->configuration->getSizeX();
                $length = $this->configuration->getSizeY();
                $width = $this->configuration->getSizeZ();
                $weight = $this->configuration->getSizeWeight();
                break;
        }

        $dimensions = array(
            "height" => $height,
            "length" => $length,
            "width" => $width,
            "weight" => $weight
        );

        return $dimensions;
    }

    /**
     * Get Google API key. If key is not defined return default.
     *
     * @return string
     */
    public function getGoogleMapApiKey()
    {
        $googleApiKey = self::DEFAULT_GOOGLE_API_KEY;

        // if (Mage::getStoreConfig(self::GOOGLE_MAP_API_KEY_XML_PATH)) {
        //     $googleApiKey = Mage::getStoreConfig(self::GOOGLE_MAP_API_KEY_XML_PATH);
        // }

        return $googleApiKey;
    }

    /**
     * Get lowest price from pricing list
     *
     * @param array $priceList
     * @param array $allRates
     * @param boot $cod
     * @return float
     */
    public function getLowestPrice($priceList)
    {
        $lowestPrice = null;

        foreach ($priceList as $carrier) {
            if ($lowestPrice == null || $lowestPrice > $carrier->price->gross) {
                $lowestPrice = $carrier->price->gross;
            }
        }

        return $lowestPrice;
    }

    /**
     * Get price for specific carrier
     *
     * @param array $priceList
     * @param array $allRates
     * @param string $carrierName
     * @param boot $cod
     * @return float|false
     */
    public function getPriceForCarrier($priceList, $allRates, $carrierName, $cod = false)
    {
        $rates = array();
        foreach ($allRates as $rate) {
            $rates[$rate->getCode()] = $rate;
        }

        foreach ($priceList as $carrier) {
            if ($carrier->operatorName == $carrierName
                && $rates['sendit_bliskapaczka_' . $carrierName . ($cod ? '_COD' : '')]
            ) {
                return $this->_getPriceWithCartRules($carrier, $rates, $cod);
            }
        }

        return false;
    }

    /**
     * Get price with applied cart rules
     *
     * @param sdtClass $carrier
     * @param array $rates
     * @param boot $cod
     * @return float
     */
    protected function _getPriceWithCartRules($carrier, $rates, $cod)
    {
        $price = $carrier->price->gross;
        $priceFromMagento = $rates['sendit_bliskapaczka_' . $carrier->operatorName . ($cod ? '_COD' : '')]->getPrice();
        $price = $priceFromMagento < $price ? $priceFromMagento : $price;

        return $price;
    }

    /**
     * Get operators and prices from Bliskapaczka API
     *
     * @param boot $cod
     * @param string $parcelDimensionsType
     * @return array
     */
    public function getPriceList($cod = null, $parcelDimensionsType = 'fixed')
    {
        $data = array("parcel" => array('dimensions' => $this->getParcelDimensions($parcelDimensionsType)));
        if ($cod) {
            $data['codValue'] = 1;
        }

        try {
            $apiClient = new \Bliskapaczka\ApiClient\Bliskapaczka\Pricing(
                $this->configuration->getApikey(),
                $this->configuration->getEnvironment()
            );

            $priceList = json_decode($apiClient->get($data));

            $priceListCleared = array();
            foreach ($priceList as $carrier) {
                if ($carrier->availabilityStatus == false) {
                    continue;
                }

                $priceListCleared[] = $carrier;
            }
        } catch (\Bliskapaczka\ApiClient\Exception $e) {
            $priceListCleared = array();
            // Mage::log($e->getMessage(), null, $this::LOG_FILE);
        }

        return $priceListCleared;
    }

    /**
     * Get widget configuration
     *
     * @param array $allRates
     * @param array $priceList
     * @param boot $cod
     *
     * @return string
     */
    public function getOperatorsForWidget($priceList = null, $cod = null)
    {
        if ($priceList == null) {
            $priceList = $this->getPriceList($cod);
        }

        $operators = array();

        foreach ($priceList as $carrier) {
            $operators[] = array(
                "operator" => $carrier->operatorName,
                "price"    => $carrier->price->gross
            );
        }

        return json_encode($operators);
    }

    /**
     * Remove all non numeric chars from phone number
     *
     * @param string $phoneNumber
     * @return string
     */
    public function telephoneNumberCleaning($phoneNumber)
    {
        $phoneNumber = preg_replace("/[^0-9]/", "", $phoneNumber);

        if (strlen($phoneNumber) > 9) {
            $phoneNumber = preg_replace("/^48/", "", $phoneNumber);
        }
        
        return $phoneNumber;
    }
}
