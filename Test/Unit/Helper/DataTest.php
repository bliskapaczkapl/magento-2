<?php

namespace Magento\Framework\App\Helper;

/**
 * Pseudo mock class
 */
class AbstractHelper {}

namespace Sendit\Bliskapaczka\Test\Unit\Helper;

use \PHPUnit\Framework\TestCase;
use \Sendit\Bliskapaczka\Model\Api\ConfigurationInterface;

/**
 * Pseudo mock class
 */
class Configuration implements ConfigurationInterface {
    public static function fromStoreConfiguration() {
        $configuration = new class() {
            public $property;

            public function getSizeX() { return 12; }
            public function getSizeY() { return 12; }
            public function getSizeZ() { return 16; }
            public function getSizeWeight() { return 1; }
        };

        return $configuration;
    }
}

/**
 * Pseudo mock class
 */
class Pricing {}

/**
 * Tests for order helper
 */
class DataTest extends TestCase
{
    /**
     * @dataProvider pricings
     */
    public function testGetPriceList($pricing, $parcelDimensionsType, $deliveryType, $dimensions, $expectedValue)
    {
        $helper = $this->getMockBuilder(\Sendit\Bliskapaczka\Helper\Data::class)
            ->setConstructorArgs(array(new Configuration()))
            ->setMethods(
                array(
                    'getPricing',
                    'getApiClientPricing',
                    'getParcelDimensions'
                )
            )
            ->getMock();

        $helper->method('getPricing')->willReturn($this->getPricing());

        $apiClientOrder = $this->getApiClientPricing();
        $apiClientOrder->method('get')->will($this->returnValue($pricing));
        $helper->method('getApiClientPricing')->willReturn($apiClientOrder);

        $helper->expects($this->once())
             ->method('getParcelDimensions')
             ->with($parcelDimensionsType)
             ->will($this->returnValue($dimensions));

        // $helper = new \Sendit\Bliskapaczka\Helper\Data(new Configuration());
        $pricing = $helper->getPriceList(null, $parcelDimensionsType, $deliveryType);

        if (empty($expectedValue)) {
            $this->assertEquals($expectedValue, $pricing);
        } else {
            $this->assertTrue(is_array($pricing));
            $this->assertEquals($expectedValue[0]->price->gross, $pricing[0]->price->gross);
            $this->assertEquals($expectedValue[0]->operatorName, $pricing[0]->operatorName);
        }
    }

    protected function getPricing()
    {
        $dpd = new class() {};
        $dpd->operatorName = 'DPD';
        $dpd->operatorFullName = 'DPD';

        $ruch = new class() {};
        $ruch->operatorName = 'RUCH';
        $ruch->operatorFullName = 'Ruch';

        $poczta = new class() {};
        $poczta->operatorName = 'POCZTA';
        $poczta->operatorFullName ='Poczta Polska';

        $inpost = new class() {};
        $inpost->operatorName = 'INPOST';
        $inpost->operatorFullName ='Inpost';

        return array(
            $dpd,
            $ruch,
            $poczta,
            $inpost
        );
    }

    protected function getApiClientPricing()
    {
        $apiClientOrder = $this->getMockBuilder(Pricing::class)
                                     ->disableOriginalConstructor()
                                     ->disableOriginalClone()
                                     ->disableArgumentCloning()
                                     ->disallowMockingUnknownTypes()
                                     ->setMethods(array('get'))
                                     ->getMock();

        return $apiClientOrder;
    }

    public function pricings()
    {
        $defaultDimensions = array(
            "height" => 12,
            "length" => 12,
            "width" => 16,
            "weight" => 1
        );

        $price = new class() {};
        $price->gross = 5.99;
        $price->net = 4.87;
        $price->vat = 1.12;

        $dpd = new class() {};
        $dpd->availabilityStatus = true;
        $dpd->operatorName = 'DPD';
        $dpd->operatorFullName = 'DPD';
        $dpd->price = $price;

        $pocztaP2d = new class() {};
        $pocztaP2d->availabilityStatus = true;
        $pocztaP2d->operatorName = 'POCZTA_P2D';
        $pocztaP2d->operatorFullName = 'Poczta Polska - Kurier 48';
        $pocztaP2d->price = $price;

        return [
            ['', 'fixed', 'P2P', $defaultDimensions, []],
            ['[]', 'fixed', 'P2P', $defaultDimensions, []],
            [
                '[{"availabilityStatus":false, "operatorName":"DPD", "price":{"net":4.87,"vat":1.12,"gross":5.99}}]',
                'fixed',
                'P2P',
                $defaultDimensions,
                []
            ],
            [
                '[{"availabilityStatus":true, "operatorName":"DPD", "price":{"net":4.87,"vat":1.12,"gross":5.99}}]',
                'fixed',
                'P2P',
                $defaultDimensions,
                [$dpd]
            ],
            [
                '[{"availabilityStatus":true, "operatorName":"DPD", "price":{"net":4.87,"vat":1.12,"gross":5.99}}]',
                'default',
                'P2P',
                $defaultDimensions,
                [$dpd]
            ],
            [
                '[{"availabilityStatus":true, "operatorName":"POCZTA", "price":{"net":4.87,"vat":1.12,"gross":5.99}}]',
                'default',
                'P2D',
                $defaultDimensions,
                [$pocztaP2d]
            ]

        ];
    }
}
