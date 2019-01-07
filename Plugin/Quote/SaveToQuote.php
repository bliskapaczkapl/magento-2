<?php
/**
* *
*  @author DCKAP Team
*  @copyright Copyright (c) 2018 DCKAP (https://www.dckap.com)
*  @package Dckap_CustomFields
*/

namespace Sendit\Bliskapaczka\Plugin\Quote;

use Magento\Quote\Model\QuoteRepository;
use Sendit\Bliskapaczka\ApiClient\Bliskapaczka\Order;

/**
* Class SaveToQuote
* @package Dckap\CustomFields\Plugin\Quote
*/
class SaveToQuote
{
   /**
    * @var QuoteRepository
    */
   protected $quoteRepository;

   protected $apiClient;

   /**
    * SaveToQuote constructor.
    * @param QuoteRepository $quoteRepository
    */
   public function __construct(
       QuoteRepository $quoteRepository
   ) {
       $this->quoteRepository = $quoteRepository;
       $this->apiClient = new Order('782aff82-344b-4f48-9d13-d5913b6b818a', 'test');
   }

   /**
    * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
    * @param $cartId
    * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    */
   public function beforeSaveAddressInformation(
       \Magento\Checkout\Model\ShippingInformationManagement $subject,
       $cartId,
       \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
   ) {
       if(!$extAttributes = $addressInformation->getExtensionAttributes())
           return;

       $quote = $this->quoteRepository->getActive($cartId);
//       $orderData = [
//           "senderFirstName" => "Paweł",
//           "senderLastName" => "Karbowniczek",
//           "senderPhoneNumber" => "512813267",
//           "senderEmail" => "pkarbowniczek@divante.pl",
//           "senderStreet" => "Dmowskiego",
//           "senderBuildingNumber" => "17",
//           "senderFlatNumber" => "",
//           "senderPostCode" => "50-203",
//           "senderCity" => "Wrocłąw",
//           "receiverFirstName" => "Paweł",
//           "receiverLastName" => "Karbowniczek",
//           "receiverPhoneNumber" => "512813267",
//           "receiverEmail" => "pkarbowniczek@divante.pl",
//           "operatorName" => $extAttributes->getPosOperator(),
//           "destinationCode" => $extAttributes->getPosCode(),
//           "postingCode" => "WRO206",
//           "codValue" => 1,
//           "insuranceValue" => 0,
//           "additionalInformation" => $extAttributes->getPosCodeDescription(),
//           "parcel" => [
//               "dimensions" => [
//                   "height" => 20,
//                   "length" => 20,
//                   "width" => 20,
//                   "weight" => 2
//               ]
//           ],
//           "deliveryType" => "P2P"
//       ];

       $quote->setPosOperator($extAttributes->getPosOperator());
       $quote->setPosCode($extAttributes->getPosCode());
       $quote->setPosCodeDescription($extAttributes->getPosCodeDescription());

   }
}
