<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 18.12.18
 * Time: 10:11
 */

namespace Sendit\Bliskapaczka\Model\Api;

class ReceiverConfiguration
{
    /** @var string */
    protected $receiverFirstName;
    /** @var string */
    protected $receiverLastName;
    /** @var string */
    protected $receiverPhoneNumber;
    /** @var string */
    protected $receiverEmail;
    /** @var string */
    protected $receiverStreet;
    /** @var string */
    protected $receiverBuildingNumber;
    /** @var string */
    protected $receiverFlatNumber;
    /** @var string */
    protected $receiverPostCode;
    /** @var string */
    protected $receiverCity;
    /** @var string */
    protected $receiverCountryCode;
    /** @var string */
    protected $operatorName;
    /** @var string */
    protected $destinationCode;
    /** @var string */
    protected $additionalInformation;


    private function __construct(){}

    /**
     * @return ReceiverConfiguration
     */
    public static function fromQuote($quote)
    {
        $address = $quote->getShippingAddress();
        $addressData = $address->toArray();

        $rc = new ReceiverConfiguration;
        $rc->receiverFirstName = $addressData['firstname'];
        $rc->receiverLastName = $addressData['lastname'];
        $rc->receiverPhoneNumber = $addressData['telephone'];
        $rc->receiverEmail = $addressData['email'];
        $rc->receiverStreet = explode(PHP_EOL,$addressData["street"])[0];
        $rc->receiverBuildingNumber = explode(PHP_EOL,$addressData["street"])[1];
        $rc->receiverFlatNumber = explode(PHP_EOL,$addressData["street"])[2];
        $rc->receiverPostCode = $addressData['postcode'];
        $rc->receiverCity = $addressData['city'];
        $rc->receiverCountryCode = $addressData['country_id'];
        $rc->operatorName = $quote->getPosOperator();
        $rc->destinationCode = $quote->getPosCode();
        $rc->additionalInformation = $quote->getPosCodeDescription();
        return $rc;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'receiverFirstName' => $this->receiverFirstName,
            'receiverLastName' => $this->receiverLastName,
            'receiverPhoneNumber' => $this->receiverPhoneNumber,
            'receiverEmail' => $this->receiverEmail,
            'receiverStreet' => $this->receiverStreet,
            'receiverBuildingNumber' => $this->receiverBuildingNumber,
            'receiverFlatNumber' => $this->receiverFlatNumber,
            'receiverPostCode' => $this->receiverPostCode,
            'receiverCity' => $this->receiverCity,
            'receiverCountryCode' => $this->receiverCountryCode,
            'operatorName' => $this->operatorName,
            'destinationCode' => $this->destinationCode,
            'additionalInformation' => $this->additionalInformation
        ];
    }
}