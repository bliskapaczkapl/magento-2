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


   /**
    * SaveToQuote constructor.
    * @param QuoteRepository $quoteRepository
    */
   public function __construct(
       QuoteRepository $quoteRepository
   ) {
       $this->quoteRepository = $quoteRepository;
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
       if(!$extAttributes = $addressInformation->getExtensionAttributes()) {
           return;
       }

       $deliiveryType = 'P2P';
       if ($addressInformation->getShippingCarrierCode() === 'courier') {
           $deliiveryType = 'D2D';
           if ($extAttributes->getPosOperator() === 'POCZTA') {
               $deliiveryType = 'P2D';
           }
       }
       $quote = $this->quoteRepository->getActive($cartId);
       $quote->setPosOperator($extAttributes->getPosOperator());
       $quote->setPosCode($extAttributes->getPosCode());
       $quote->setPosCodeDescription($extAttributes->getPosCodeDescription());
       $quote->setDeliveryType($deliiveryType);

   }
}
