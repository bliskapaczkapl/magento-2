<?php

namespace Sendit\Bliskapaczka\Model\Api\Configuration;

use \Sendit\Bliskapaczka\Model\Api\Configuration;

interface ConfigurationInterface
{
    public function set(
        Configuration $configuration,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) : Configuration;
}
