<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 12.12.18
 * Time: 09:36
 */

namespace Sendit\Bliskapaczka\Model\Api;


class Order
{
    /** @var string */
    protected $number;
    /** @var string */
    protected $senderFirstName;
    /** @var string */
    protected $senderLastName;
    /** @var string */
    protected $senderPhoneNumber;
    /** @var string */
    protected $senderStreet;
    /** @var string */
    protected $senderBuildingNumber;
    /** @var string */
    protected $senderFlatNumber;
    /** @var string */
    protected $senderPostCode;
    /** @var string */
    protected $senderCity;
    /** @var string */
    protected $receiverFirstName;
    /** @var string */
    protected $receiverLastName;
    /** @var string */
    protected $receiverPhoneNumber;
    /** @var string */
    protected $receiverEmail;
    /** @var string */
    protected $operatorName;
    /** @var string */
    protected $destinationCode;
    /** @var  string*/
    protected $postingCode;
}