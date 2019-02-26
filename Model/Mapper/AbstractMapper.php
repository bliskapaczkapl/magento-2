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

        if ($this->configuration->senderFlatNumber) {
            $data['senderFlatNumber'] = $this->configuration->senderFlatNumber;
        }

        if ($this->configuration->senderPostCode) {
            $data['senderPostCode'] = $this->configuration->senderPostCode;
        }

        if ($this->configuration->senderCity) {
            $data['senderCity'] = $this->configuration->senderCity;
        }

        return $data;
    }
}
