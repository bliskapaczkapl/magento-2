<?php

namespace Sendit\Bliskapaczka\ApiClient\Validator\Order;

use Sendit\Bliskapaczka\ApiClient\Validator\Order;
use Sendit\Bliskapaczka\ApiClient\AbstractValidator;
use Sendit\Bliskapaczka\ApiClient\ValidatorInterface;
use Sendit\Bliskapaczka\ApiClient\Exception;

/**
 * Order Validator class
 *
 * @author  Mateusz Koszutowski (mkoszutowski@divante.pl)
 * @version 0.1.0
 */
class Advice extends Order implements ValidatorInterface
{
    /**
     * Basic validation for data
     */
    protected function validationByProperty()
    {
        foreach ($this->properties as $property => $settings) {
            if (!isset($this->data[$property]) && isset($settings['notblank']) && $settings['notblank'] === true) {
                throw new Exception($property . " is required", 1);
            }

            $this->notBlank($property, $settings);
            $this->maxLength($property, $settings);
            $this->specificValidation($property);
        }
    }
}
