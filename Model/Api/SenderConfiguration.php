<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 17.12.18
 * Time: 08:18
 */

namespace Sendit\Bliskapaczka\Model\Api;

class SenderConfiguration
{
    /** @var string */
    protected $senderFirstName;
    /** @var string */
    protected $senderLastName;
    /** @var string */
    protected $senderEmail;
    /** @var string */
    protected $senderPhoneNumber;
    /** @var string */
    protected $senderStreet;
    /** @var string */
    protected $senderBuildingNumber;
    /** @var string */
    protected $senderFlatNumber;
    /** @var string */
    protected $senderPostCode;
    /** @var string */
    protected $senderCity;
    /** @var string */
    protected $countryCode;
    /** @var string */
    protected $cod;
    /** @var string */
    protected $height;
    /** @var string */
    protected $length;
    /** @var string */
    protected $width;
    /** @var string */
    protected $weight;

    private function __construct()
    {
    }

    /**
     * @return SenderConfiguration
     * @throws \Exception
     */
    public static function fromStoreConfiguration()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $scopeConfig = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface');
        $fname = $scopeConfig
            ->getValue('carriers/bliskapaczka/sender_first_name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $lname = $scopeConfig
            ->getValue('carriers/bliskapaczka/sender_last_name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $email = $scopeConfig
            ->getValue('carriers/bliskapaczka/sender_email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $phoneNumber = $scopeConfig
            ->getValue('carriers/bliskapaczka/sender_phone_number', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $street = $scopeConfig
            ->getValue('carriers/bliskapaczka/sender_street', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $bNumber = $scopeConfig
            ->getValue(
                'carriers/bliskapaczka/sender_building_number',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );

        $fNumber = $scopeConfig
            ->getValue('carriers/bliskapaczka/sender_flat_number', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $postCode = $scopeConfig
            ->getValue('carriers/bliskapaczka/sender_post_code', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $city = $scopeConfig
            ->getValue('carriers/bliskapaczka/sender_city', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $countryCode = 'PL';
        $cod = $scopeConfig
            ->getValue('carriers/bliskapaczka/sender_cod', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $height = $scopeConfig
            ->getValue('carriers/bliskapaczka/size_x', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $length = $scopeConfig
            ->getValue('carriers/bliskapaczka/size_y', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $width = $scopeConfig
            ->getValue('carriers/bliskapaczka/size_z', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $weight = $scopeConfig
            ->getValue('carriers/bliskapaczka/weight', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $sc = new SenderConfiguration;
        $sc->senderFirstNameValidator($fname);
        $sc->senderLastNameValidator($lname);
        $sc->senderPhoneNumberValidator($phoneNumber);
        $sc->senderEmailValidator($email);
        $sc->senderStreetValidator($street);
        $sc->senderBuildingNumberValidator($bNumber);
        $sc->senderFlatNumberValidator($fNumber);
        $sc->senderPostCodeValidator($postCode);
        $sc->senderCityValidator($city);

        $sc->senderFirstName = $fname;
        $sc->senderLastName = $lname;
        $sc->senderPhoneNumber = $phoneNumber;
        $sc->senderEmail = $email;
        $sc->senderStreet = $street;
        $sc->senderBuildingNumber = $bNumber;
        $sc->senderFlatNumber = $fNumber;
        $sc->senderPostCode = $postCode;
        $sc->senderCity = $city;
        $sc->countryCode = $countryCode;
        $sc->cod = $cod;
        $sc->height = $height;
        $sc->length = $length;
        $sc->width = $width;
        $sc->weight= $weight;

        return $sc;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            "senderFirstName" => $this->senderFirstName,
            "senderLastName" => $this->senderLastName,
            "senderPhoneNumber" => $this->senderPhoneNumber,
            "senderEmail" => $this->senderEmail,
            "senderStreet" => $this->senderStreet,
            "senderBuildingNumber" => $this->senderBuildingNumber,
            "senderFlatNumber" => $this->senderFlatNumber,
            "senderPostCode" => $this->senderPostCode,
            "senderCity" => $this->senderCity,
            'countryCode' => $this->countryCode,
            'codValue' => $this->cod,
            'parcel' => [
                'dimensions' => [
                    'height' => $this->height,
                    'length' => $this->length,
                    'weight' => $this->weight,
                    'width' => $this->width
                ]
            ]
        ];
    }

    /**
     * @param string $senderFirstName
     * @throws \Exception
     */
    protected function senderFirstNameValidator(string $senderFirstName)
    {
        $this->valueIsRequire($senderFirstName, 'sender first name');
        $this->maxLength($senderFirstName, 30, 'sender first name');
    }

    /**
     * @param string $senderLastName
     * @throws \Exception
     */
    protected function senderLastNameValidator(string $senderLastName)
    {
        $this->valueIsRequire($senderLastName, 'sender last name');
        $this->maxLength($senderLastName, 30, 'sender last name');
    }

    /**
     * @param string $senderPhoneNumber
     * @throws \Exception
     */
    protected function senderPhoneNumberValidator(string $senderPhoneNumber)
    {
        $this->valueIsRequire($senderPhoneNumber, 'sender phone number');
        $this->maxLength($senderPhoneNumber, 30, 'sender phone number');
    }

    protected function senderEmailValidator(string $senderEmail)
    {
        $this->valueIsRequire($senderEmail, 'sender email');
        $this->maxLength($senderEmail, 60, 'sender email');
    }
    /**
     * @param string $senderStreet
     * @throws \Exception
     */
    protected function senderStreetValidator(string $senderStreet)
    {
        $this->valueIsRequire($senderStreet, 'sender street');
        $this->maxLength($senderStreet, 30, 'sender street');
    }

    /**
     * @param string $senderBuildingNumber
     * @throws \Exception
     */
    protected function senderBuildingNumberValidator(string $senderBuildingNumber)
    {
        $this->valueIsRequire($senderBuildingNumber, 'sender building number');
        $this->maxLength($senderBuildingNumber, 10, 'sender building number');
    }

    /**
     * @param mixed $senderFlatNumber
     * @throws \Exception
     */
    protected function senderFlatNumberValidator($senderFlatNumber)
    {
        if (mb_strlen($senderFlatNumber) > 0) {
            $this->maxLength($senderFlatNumber, 10, 'sender building number');
        }
    }

    /**
     * @param string $senderPostCode
     * @throws \Exception
     */
    protected function senderPostCodeValidator(string $senderPostCode)
    {
        $this->valueIsRequire($senderPostCode, 'sender post code');
        $this->maxLength($senderPostCode, 10, 'sender post code');
    }

    protected function senderCityValidator(string $senderCity)
    {
        $this->valueIsRequire($senderCity, 'sender city');
        $this->maxLength($senderCity, 30, 'sender city');
    }

    /**
     * @param mixed $value
     * @param string $fieldName
     * @throws \Exception
     */
    protected function valueIsRequire($value, string $fieldName)
    {
        if (mb_strlen($value) == 0) {
            throw new \Exception(sprintf('Field %s is require', $fieldName));
        }
    }

    /**
     * @param string $value
     * @param int $length
     * @param string $fieldName
     * @throws \Exception
     */
    protected function maxLength(string $value, int $length, string $fieldName)
    {
        if (mb_strlen($value) > $length) {
            throw new \Exception(sprintf('Field %s must have less characters (< %s)', $fieldName, $length));
        }
    }
}
