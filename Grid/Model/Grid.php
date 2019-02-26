<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 20.11.18
 * Time: 12:41
 */

namespace Sendit\Bliskapaczka\Grid\Model;

//use Webkul\Grid\Api\Data\GridInterface;
/**
 * Grid of bliskapaczka.pl on order page
 */
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

    /**
     * @return Grid
     */
    public function getNumber()
    {
         return $this->getData(self::NUMBER);
    }

    /**
     * @param $number
     * @return Grid
     */
    public function setNumber($number)
    {
        return $this->setData(self::NUMBER, $number);
    }

    /**
     * @return Grid
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @param $status
     * @return Grid
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @param $deliveryType
     * @return Grid
     */
    public function setDeliveryType($deliveryType)
    {
        return $this->setData(self::DELIVERY_TYPE, $deliveryType);
    }

    /**
     * @return Grid
     */
    public function getDeliveryType()
    {
        return $this->getData(self::DELIVERY_TYPE);
    }

    /**
     * @param $trackingNumber
     * @return Grid
     */
    public function setTrackingNumber($trackingNumber)
    {
        return $this->setData(self::TRACKING_NUMBER, $trackingNumber);
    }

    /**
     * @return Grid
     */
    public function getTrackingNumber()
    {
        return $this->getData(self::TRACKING_NUMBER);
    }

    /**
     * @param $adviceDate
     * @return Grid
     */
    public function setAdviceDate($adviceDate)
    {
        return $this->setData(self::ADVICE_DATE, $adviceDate);
    }

    /**
     * @return Grid
     */
    public function getAdviceDate()
    {
        return $this->getData(self::ADVICE_DATE);
    }

    /**
     * @param $creationDate
     * @return Grid
     */
    public function setCreationDate($creationDate)
    {
        return $this->setData(self::CREATION_DATE, $creationDate);
    }

    /**
     * @param $orderId
     * @return Grid
     */
    public function getCreationDate()
    {
        return $this->getData(self::CREATION_DATE);
    }
}
