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

    private function __construct()
    {}

    public static function fromStoreConfiguration()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $scopeConfig = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface');

        $apiKey = $scopeConfig
            ->getValue('carriers/bliskapaczka/api_key', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $testMode = $scopeConfig
            ->getValue('carriers/bliskapaczka/sandbox', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $sizeX = $scopeConfig
            ->getValue('carriers/bliskapaczka/size_x', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $sizeY = $scopeConfig
            ->getValue('carriers/bliskapaczka/size_y', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $sizeZ = $scopeConfig
            ->getValue('carriers/bliskapaczka/size_z', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $sizeWeight = $scopeConfig
            ->getValue('carriers/bliskapaczka/weight', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $environment = 'test';
        if ($testMode == 0) {
            $environment = 'prod';
        }

        $configuration = new Configuration;
        // $configuration->validateApiKey($apiKey);
        // $configuration->validateEnvironment($environment);
        $configuration->apikey = $apiKey;
        $configuration->environment = $environment;
        $configuration->sizeX = $sizeX;
        $configuration->sizeY = $sizeY;
        $configuration->sizeZ = $sizeZ;
        $configuration->sizeWeight = $sizeWeight;

        return $configuration;
    }

    /**
     * Magento style getter from https://stackoverflow.com/questions/4478661/getter-and-setter
     * 
     * @param 
     */
    public function __call($name, $arguments) {
        //Getting and setting with $this->property($optional);
        if (property_exists(get_class($this), $name)) {
            //Always set the value if a parameter is passed
            if (count($arguments) == 1) {
                /* set */
                $this->$name = $arguments[0];
            } else if (count($arguments) > 1) {
                throw new \Exception("Setter for $name only accepts one parameter.");
            }

            //Always return the value (Even on the set)
            return $this->$name;
        }

        //If it doesn't chech if its a normal old type setter ot getter
        //Getting and setting with $this->getProperty($optional);
        //Getting and setting with $this->setProperty($optional);
        $prefix = substr($name, 0, 3);
        $property = strtolower($name[3]) . substr($name, 4);
        switch ($prefix) {
            case 'get':
                return $this->$property;
                break;
            case 'set':
                //Always set the value if a parameter is passed
                if (count($arguments) != 1) {
                    throw new \Exception("Setter for $name requires exactly one parameter.");
                }
                $this->$property = $arguments[0];
                //Always return the value (Even on the set)
                return $this->$name;
            default:
                throw new \Exception("Property $name doesn't exist.");
                break;
        }
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