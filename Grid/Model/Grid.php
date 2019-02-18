<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 20.11.18
 * Time: 12:41
 */

namespace Sendit\Bliskapaczka\Grid\Model;

//use Webkul\Grid\Api\Data\GridInterface;

class Grid extends \Magento\Framework\Model\AbstractModel
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'sendit_bliskapaczka_order';

    /**
     * @var string
     */
    protected $_cacheTag = 'sendit_bliskapaczka_order';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'sendit_bliskapaczka_order';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('Sendit\Bliskapaczka\Grid\Model\ResourceModel\Grid');
    }


    /**
     * @return mixed
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }


    /**
     * @param int $entityId
     * @return \Magento\Framework\Model\AbstractModel|Grid
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }


    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->getData(self::ORDER_ID);
    }


    /**
     * @param $orderId
     * @return Grid
     */
    public function setOderId($orderId)
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    public function getNumber()
    {
         return $this->getData(self::NUMBER);
    }

    public function setNumber($number)
    {
        return $this->setData(self::NUMBER, $number);
    }

    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    public function setDeliveryType($deliveryType)
    {
        return $this->setData(self::DELIVERY_TYPE, $deliveryType);
    }

    public function getDeliveryType()
    {
        return $this->getData(self::DELIVERY_TYPE);
    }

    public function setTrackingNumber($trackingNumber)
    {
        return $this->setData(self::TRACKING_NUMBER, $trackingNumber);
    }

    public function getTrackingNumber()
    {
        return $this->getData(self::TRACKING_NUMBER);
    }

    public function setAdviceDate($adviceDate)
    {
        return $this->setData(self::ADVICE_DATE, $adviceDate);
    }

    public function getAdviceDate()
    {
        return $this->getData(self::ADVICE_DATE);
    }

    public function setCreationDate($creationDate)
    {
        return $this->setData(self::CREATION_DATE, $creationDate);
    }

    public function getCreationDate()
    {
        return $this->getData(self::CREATION_DATE);
    }
}
