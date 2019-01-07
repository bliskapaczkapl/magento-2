<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 11.12.18
 * Time: 12:21
 */

namespace Sendit\Bliskapaczka\Model\Api;


class Configuration
{
    protected const ALLOWS_ENV = ['prod', 'test'];
    /** @var string */
    protected $apikey;
    /** @var string */
    protected $environment;

    private function __construct(){}

    public static function fromStoreConfiguration()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $scopeConfig = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface');
        $apiKey = $scopeConfig
            ->getValue('carriers/bliskapaczka/api_key', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $testMode = $scopeConfig
            ->getValue('carriers/bliskapaczka/sandbox', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $environment = 'test';
        if ($testMode == 0) {
            $environment = 'prod';
        }
        $conf = new Configuration;
        $conf->validateApiKey($apiKey);
        $conf->validateEnvironment($environment);
        $conf->apikey = $apiKey;
        $conf->environment = $environment;
        return $conf;
    }

    /**
     * @return string
     */
    public function getApikey(): string
    {
        return $this->apikey;
    }

    /**
     * @return string
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * @param string $apikey
     * @throws \Exception
     */
    protected function validateApiKey(string $apikey) {
        if (!is_string($apikey) || empty($apikey)) {
            throw new \Exception('Api key is not validate');
        }
    }

    /**
     * @param string $environment
     * @throws \Exception
     */
    protected function validateEnvironment(string $environment) {
        if (!in_array($environment, self::ALLOWS_ENV)) {
            throw new \Exception('Environment is not allow');
        }
    }
}