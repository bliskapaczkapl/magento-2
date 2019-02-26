<?php

namespace Sendit\Bliskapaczka\Model\Api\Configuration;

/**
 * Sender configuration class for bliskapaczka shippine method
 */
class Sender extends \Sendit\Bliskapaczka\Model\Api\Configuration implements ConfigurationInterface
{
    /**
     * Map configuration from database to class variable
     *
     * @param \Sendit\Bliskapaczka\Model\Api\Configuration $configuration
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @return \Sendit\Bliskapaczka\Model\Api\Configuration
     */
    public function set(
        \Sendit\Bliskapaczka\Model\Api\Configuration $configuration,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) : \Sendit\Bliskapaczka\Model\Api\Configuration {
        $firstname = $scopeConfig
            ->getValue('carriers/bliskapaczka/sender_first_name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $lastname = $scopeConfig
            ->getValue('carriers/bliskapaczka/sender_last_name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $email = $scopeConfig
            ->getValue('carriers/bliskapaczka/sender_email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $phoneNumber = $scopeConfig
            ->getValue('carriers/bliskapaczka/sender_phone_number', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $street = $scopeConfig
            ->getValue('carriers/bliskapaczka/sender_street', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $buildingNumber = $scopeConfig
            ->getValue(
                'carriers/bliskapaczka/sender_building_number',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );

        $flatNumber = $scopeConfig
            ->getValue('carriers/bliskapaczka/sender_flat_number', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $postCode = $scopeConfig
            ->getValue('carriers/bliskapaczka/sender_post_code', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $city = $scopeConfig
            ->getValue('carriers/bliskapaczka/sender_city', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $countryCode = 'PL';
        $cod = $scopeConfig
            ->getValue('carriers/bliskapaczka/sender_cod', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $configuration->senderFirstName = $firstname;
        $configuration->senderLastName = $lastname;
        $configuration->senderPhoneNumber = $phoneNumber;
        $configuration->senderEmail = $email;
        $configuration->senderStreet = $street;
        $configuration->senderBuildingNumber = $buildingNumber;
        $configuration->senderFlatNumber = $flatNumber;
        $configuration->senderPostCode = $postCode;
        $configuration->senderCity = $city;
        $configuration->countryCode = $countryCode;

        return $configuration;
    }
}
