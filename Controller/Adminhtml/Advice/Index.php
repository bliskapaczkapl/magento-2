<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 07.01.19
 * Time: 14:08
 */

namespace Sendit\Bliskapaczka\Controller\Adminhtml\Advice;

use Sendit\Bliskapaczka\Model\Api\Configuration;
use Bliskapaczka\ApiClient\Bliskapaczka\Order as BliskapaczkaOrder;
use Bliskapaczka\ApiClient\Bliskapaczka\Todoor as BliskapaczkaTodoor;
use Bliskapaczka\ApiClient\Bliskapaczka\Order\Advice as BliskapaczkaOrderAdvice;
use Bliskapaczka\ApiClient\Bliskapaczka\Order\Todoor as BliskapaczkaOrderTodoor;
use Sendit\Bliskapaczka\Model\Mapper\Order;
use Sendit\Bliskapaczka\Model\Mapper\Todoor;
use Sendit\Bliskapaczka\Helper\Data as SenditHelper;

/**
 * Controller class for advice action
 */
class Index extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * Construct method
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->resultFactory = $resultJsonFactory;

        $this->todoor = $this->_objectManager->get('Sendit\Bliskapaczka\Model\Mapper\Todoor');
        $this->order = $this->_objectManager->get('Sendit\Bliskapaczka\Model\Mapper\Order');
    }

    /**
     * Advice order
     */
    public function execute()
    {
        try {
            $resultRedirect = $this->resultRedirectFactory->create();

            $orderId = $this->getRequest()->getParam('order_id');
            $order = $this->_objectManager->get('Magento\Sales\Model\Order')->load($orderId);

            $configuration = Configuration::fromStoreConfiguration();

            if ($order->getPosCode()) {
                $apiClient = new BliskapaczkaOrderAdvice(
                    $configuration->getApikey(),
                    $configuration->getEnvironment()
                );

                $mapper = $this->order;
            } else {
                $apiClient = new BliskapaczkaTodoorAdvice(
                    $configuration->getApikey(),
                    $configuration->getEnvironment()
                );

                $mapper = $this->todoor;
            }

            $data = $mapper->getData($order);

            $apiClient->setOrderId($order->getNumber());
            $response = $apiClient->create($data);
            $response = json_decode($response);
            $order->setData("tracking_number", $response->trackingNumber);
            $order->setData("advice_date", $response->adviceDate);
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
}
