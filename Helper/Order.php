<?php

namespace Sendit\Bliskapaczka\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;

/**
 * Bliskapaczka helper for order
 *
 * @author Mateusz Koszutowski (mkoszutowski@divante.pl)
 */
class Order extends AbstractHelper
{
    const NEW_STATUS                     = 'NEW';
    const SAVED                          = 'SAVED';
    const WAITING_FOR_PAYMENT            = 'WAITING_FOR_PAYMENT';
    const PAYMENT_CONFIRMED              = 'PAYMENT_CONFIRMED';
    const PAYMENT_REJECTED               = 'PAYMENT_REJECTED';
    const PAYMENT_CANCELLATION_ERROR     = 'PAYMENT_CANCELLATION_ERROR';
    const PROCESSING                     = 'PROCESSING';
    const ADVISING                       = 'ADVISING';
    const ERROR                          = 'ERROR';
    const READY_TO_SEND                  = 'READY_TO_SEND';
    const POSTED                         = 'POSTED';
    const ON_THE_WAY                     = 'ON_THE_WAY';
    const READY_TO_PICKUP                = 'READY_TO_PICKUP';
    const OUT_FOR_DELIVERY               = 'OUT_FOR_DELIVERY';
    const DELIVERED                      = 'DELIVERED';
    const REMINDER_SENT                  = 'REMINDER_SENT';
    const PICKUP_EXPIRED                 = 'PICKUP_EXPIRED';
    const AVIZO                          = 'AVIZO';
    const CLAIMED                        = 'CLAIMED';
    const RETURNED                       = 'RETURNED';
    const ARCHIVED                       = 'ARCHIVED';
    const OTHER                          = 'OTHER';
    const MARKED_FOR_CANCELLATION_STATUS = 'MARKED_FOR_CANCELLATION';
    const CANCELED                       = 'CANCELED';

    const GENERIC_ADVICE_ERROR           = 'GENERIC_ADVICE_ERROR';
    const AUTHORIZATION_ERROR            = 'AUTHORIZATION_ERROR';
    const LABEL_GENERATION_ERROR         = 'LABEL_GENERATION_ERROR';
    const WAYBILL_PROCESS_ERROR          = 'WAYBILL_PROCESS_ERROR';
    const BACKEND_ERROR                  = 'BACKEND_ERROR';

    const GENERIC_ADVICE_ERROR_FOR_HUMANS = "Error (Operator's advising)";
    const LABEL_GENERATION_ERROR_FOR_HUMANS = "Error (Label generating)";
    const WAYBILL_PROCESS_ERROR_FOR_HUMANS = "Error (Label processing)";
    const AUTHORIZATION_ERROR_FOR_HUMANS = "Error (Access data)";
    const BACKEND_ERROR_FOR_HUMANS = "Error (Unrecognized)";

    /**
     * Waybill NOT possible statuses
     *
     * @var array
     */
    protected $_waybillUnavailableStatuses = array(
        self::NEW_STATUS,
        self::SAVED,
        self::WAITING_FOR_PAYMENT,
        self::PAYMENT_CONFIRMED,
        self::PAYMENT_REJECTED,
        self::PAYMENT_CANCELLATION_ERROR,
        self::PROCESSING,
        self::ADVISING,
        self::ERROR,
        self::CANCELED,
    );

    /**
     * Cancel possible statuses
     *
     * @var array
     */
    protected $_cancelStatuses = array(
        self::SAVED,
        self::WAITING_FOR_PAYMENT,
        self::PAYMENT_CONFIRMED,
        self::PAYMENT_REJECTED,
        self::PROCESSING,
        self::READY_TO_SEND,
        self::ERROR
    );

    /**
     * Cancel possible statuses
     *
     * @var array
     */
    protected $_sentStatuses = array(
        self::POSTED,
        self::ON_THE_WAY,
        self::READY_TO_PICKUP,
        self::OUT_FOR_DELIVERY,
        self::DELIVERED,
        self::CANCELED
    );

    /**
     * Advice possible statuses
     *
     * @var array
     */
    protected $_adviceStatuses = array(self::SAVED);

    /**
     * Retry possible statuses
     *
     * @var array
     */
    protected $_retryStatuses = array(self::ERROR);

    /**
     * Retry possible statuses
     *
     * @var array
     */
    protected $_errorReasonStatuses = array(
        self::GENERIC_ADVICE_ERROR,
        self::AUTHORIZATION_ERROR,
        self::LABEL_GENERATION_ERROR,
        self::WAYBILL_PROCESS_ERROR,
        self::BACKEND_ERROR
    );

    /**
     * @param Magento\Sales\Model\Order $order
     * @return bool
     */
    public function canCancel($order) : bool
    {
        if (in_array($order->getBliskapaczkaStatus(), $this->_cancelStatuses)) {
            return true;
        }

        return false;
    }

    /**
     * @param Magento\Sales\Model\Order $order
     * @return bool
     */
    public function canAdvice($order) : bool
    {
        if (in_array($order->getBliskapaczkaStatus(), $this->_adviceStatuses)) {
            return true;
        }

        return false;
    }

    /**
     * @param Magento\Sales\Model\Order $order
     * @return bool
     */
    public function canRetry($order) : bool
    {
        if (in_array($order->getBliskapaczkaStatus(), $this->_retryStatuses)) {
            return true;
        }

        return false;
    }

    /**
     * @param Magento\Sales\Model\Order $order
     * @return bool
     */
    public function canWaybill($order) : bool
    {
        if (in_array($order->getBliskapaczkaStatus(), $this->_waybillUnavailableStatuses)) {
            return false;
        }

        return true;
    }
}
