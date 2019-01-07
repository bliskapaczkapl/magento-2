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
    /** @var string  */
    const REGEX_ALL_ISO_3166_1 = '/(AF|AX|AL|DZ|AS|AD|AO|AI|AQ|AG|AR|AM|AW|AU|AT|AZ|BS|BH|BD|BB|BY|BE|BZ|BJ|BM|BT|BO|BQ|BA|BW|BV|BR|IO|BN|BG|BF|BI|KH|CM|CA|CV|KY|CF|TD|CL|CN|CX|CC|CO|KM|CG|CD|CK|CR|CI|HR|CU|CW|CY|CZ|DK|DJ|DM|DO|EC|EG|SV|GQ|ER|EE|ET|FK|FO|FJ|FI|FR|GF|PF|TF|GA|GM|GE|DE|GH|GI|GR|GL|GD|GP|GU|GT|GG|GN|GW|GY|HT|HM|VA|HN|HK|HU|IS|IN|ID|IR|IQ|IE|IM|IL|IT|JM|JP|JE|JO|KZ|KE|KI|KP|KR|KW|KG|LA|LV|LB|LS|LR|LY|LI|LT|LU|MO|MK|MG|MW|MY|MV|ML|MT|MH|MQ|MR|MU|YT|MX|FM|MD|MC|MN|ME|MS|MA|MZ|MM|NA|NR|NP|NL|NC|NZ|NI|NE|NG|NU|NF|MP|NO|OM|PK|PW|PS|PA|PG|PY|PE|PH|PN|PL|PT|PR|QA|RE|RO|RU|RW|BL|SH|KN|LC|MF|PM|VC|WS|SM|ST|SA|SN|RS|SC|SL|SG|SX|SK|SI|SB|SO|ZA|GS|SS|ES|LK|SD|SR|SJ|SZ|SE|CH|SY|TW|TJ|TZ|TH|TL|TG|TK|TO|TT|TN|TR|TM|TC|TV|UG|UA|AE|GB|US|UM|UY|UZ|VU|VE|VN|VG|VI|WF|EH|YE|ZM|ZW)';

    private function __construct(){}

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
            ->getValue('carriers/bliskapaczka/sender_building_number', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

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

    protected function countryCodeValidator(string $countryCode)
    {
        if (mb_strlen($countryCode) !== 0) {
            if (preg_match('/' + self::REGEX_ALL_ISO_3166_1 + '$/', $countryCode) == false) {
                throw new \Exception('Code must compatibility with ISO 3166-1');
            }
        }
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