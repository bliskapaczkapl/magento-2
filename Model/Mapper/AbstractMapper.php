<?php

namespace Sendit\Bliskapaczka\Model\Mapper;

use Sendit\Bliskapaczka\Helper\Data as SenditHelper;
use Sendit\Bliskapaczka\Model\Api\Configuration;

/**
 * Abstract class mappers
 */
abstract class AbstractMapper implements MapperInterface
{
    /**
     * Shipping constructor.
     *
     * @param SenditHelper $senditHelper
     * @param Configuration $configuration
     */
    public function __construct(
        SenditHelper $senditHelper,
        Configuration $configuration
    ) {
        $this->senditHelper = $senditHelper;
        $this->configuration = $configuration::fromStoreConfiguration();
    }

    /**
     * Prepare sender data in fomrat accptable by Bliskapaczka API
     *
     * @param array $data
     * @return array
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function _prepareSenderData($data)
    {
        if ($this->configuration->senderEmail) {
            $data['senderEmail'] = $this->configuration->senderEmail;
        }

        if ($this->configuration->senderFirstName) {
            $data['senderFirstName'] = $this->configuration->senderFirstName;
        }

        if ($this->configuration->senderLastName) {
            $data['senderLastName'] = $this->configuration->senderLastName;
        }

        if ($this->configuration->senderPhoneNumber) {
            $data['senderPhoneNumber'] = $this->senditHelper->telephoneNumberCleaning(
                $this->configuration->senderPhoneNumber
            );
        }

        if ($this->configuration->senderStreet) {
            $data['senderStreet'] = $this->configuration->senderStreet;
        }

        if ($this->configuration->senderBuildingNumber) {
            $data['senderBuildingNumber'] = $this->configuration->senderBuildingNumber;
        }

        $data['senderFlatNumber'] = $this->configuration->senderFlatNumber;

        if ($this->configuration->senderPostCode) {
            $data['senderPostCode'] = $this->configuration->senderPostCode;
        }

        if ($this->configuration->senderCity) {
            $data['senderCity'] = $this->configuration->senderCity;
        }

        return $data;
    }

    /**
     * Prepare CoD data in fomrat accptable by Bliskapaczka API
     *
     * @param array $data
     * @param Magento\Sales\Model\Order $order
     * @return array
     */
    protected function _prepareCodData($data, $order)
    {
        if (strpos($order->getShippingMethod(true)->getMethod(), '_COD') !== false) {
            $grandTotal = (string)round(floatval($order->getGrandTotal()), 2);
            $data['codValue'] = $grandTotal;
            $data['parcel']['insuranceValue'] = $grandTotal;

            if ($this->configuration->codBankAccountNumber) {
                $data['codPayoutBankAccountNumber'] = $this->configuration->codBankAccountNumber;
            }
        }

        return $data;
    }
}
