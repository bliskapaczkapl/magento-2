<?php

namespace Sendit\Bliskapaczka\Model\Mapper;

/**
 * Class to map order data to data acceptable by endpoint todoor (courier) Sendit Bliskapaczka API
 */
class Todoor extends AbstractMapper implements MapperInterface
{
    /**
     * Prepare mapped data for Bliskapaczka API
     *
     * @param Magento\Sales\Model\Order $order
     * @return array
     */
    public function getData($order)
    {
        $data = [];

        $shippingAddress = $order->getShippingAddress();

        $fullStreet = $shippingAddress->getStreet()[0];
        $street = preg_split("/\s+(?=\S*+$)/", $fullStreet);

        $data['receiverFirstName'] = $shippingAddress->getFirstname();
        $data['receiverLastName'] = $shippingAddress->getLastname();
        $data['receiverPhoneNumber'] = $this->senditHelper->telephoneNumberCleaning($shippingAddress->getTelephone());
        $data['receiverEmail'] = $shippingAddress->getEmail();
        $data['receiverStreet'] = $street[0];
        $data['receiverBuildingNumber'] = isset($street[1]) ? $street[1] : '';
        $data['receiverFlatNumber'] = '';
        $data['receiverPostCode'] = $shippingAddress->getPostcode();
        $data['receiverCity'] = $shippingAddress->getCity();

        $data['operatorName'] = str_replace('_COD', '', $order->getShippingMethod(true)->getMethod());

        $data['deliveryType'] = 'D2D';
        if ($data['operatorName'] == 'POCZTA_P2D') {
            $data['deliveryType'] = 'P2D';
            $data['operatorName'] = 'POCZTA';
        }

        $data['additionalInformation'] = $order->getIncrementId();
        // if ($reference) {
        //     $data['reference'] = $order->getIncrementId();
        // }

        $data['parcel'] = [
            'dimensions' => $this->senditHelper->getParcelDimensions('fixed')
        ];

        $data = $this->_prepareSenderData($data);
        $data = $this->_prepareCodData($data, $order);

        return $data;
    }
}
