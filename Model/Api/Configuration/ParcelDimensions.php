<?php

namespace Sendit\Bliskapaczka\Model\Api\Configuration;

use \Sendit\Bliskapaczka\Model\Api\Configuration;

class ParcelDimensions extends \Sendit\Bliskapaczka\Model\Api\Configuration implements ConfigurationInterface
{
    public function set(
        Configuration $configuration,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) : Configuration {
        $sizeX = $scopeConfig
            ->getValue('carriers/bliskapaczka/size_x', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $sizeY = $scopeConfig
            ->getValue('carriers/bliskapaczka/size_y', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $sizeZ = $scopeConfig
            ->getValue('carriers/bliskapaczka/size_z', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $sizeWeight = $scopeConfig
            ->getValue('carriers/bliskapaczka/weight', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $configuration->sizeX = $sizeX;
        $configuration->sizeY = $sizeY;
        $configuration->sizeZ = $sizeZ;
        $configuration->sizeWeight = $sizeWeight;

        return $configuration;
    }
}
