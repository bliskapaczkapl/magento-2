<?php

namespace Sendit\Bliskapaczka\Model\Api;

class Configuration
{
    public static function fromStoreConfiguration()
    {
        $parcelDimensions = new Configuration\ParcelDimensions();
        $sender = new Configuration\Sender();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $scopeConfig = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface');

        $apiKey = $scopeConfig
            ->getValue('carriers/bliskapaczka/api_key', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $testMode = $scopeConfig
            ->getValue('carriers/bliskapaczka/sandbox', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $autoAdvice = $scopeConfig
            ->getValue('carriers/bliskapaczka/auto_advice', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $environment = 'test';
        if ($testMode == 0) {
            $environment = 'prod';
        }

        $configuration = new Configuration;
        $configuration->apikey = $apiKey;
        $configuration->environment = $environment;
        $configuration->autoAdvice = (bool)$autoAdvice;

        $parcelDimensions->set($configuration, $scopeConfig);
        $sender->set($configuration, $scopeConfig);

        return $configuration;
    }

    /**
     * Magento style getter from https://stackoverflow.com/questions/4478661/getter-and-setter
     *
     * @param
     */
    public function __call($name, $arguments)
    {
        //Getting and setting with $this->property($optional);
        if (property_exists(get_class($this), $name)) {
            //Always set the value if a parameter is passed
            if (count($arguments) == 1) {
                /* set */
                $this->$name = $arguments[0];
            } elseif (count($arguments) > 1) {
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
}