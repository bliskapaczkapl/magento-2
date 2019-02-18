<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 07.01.19
 * Time: 14:08
 */

namespace Sendit\Bliskapaczka\Controller\Adminhtml\Advice;

use Bliskapaczka\ApiClient\Bliskapaczka\Order\Advice;
use Bliskapaczka\ApiClient\Bliskapaczka\Order;
use Sendit\Bliskapaczka\Model\Api\Configuration;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->resultFactory = $resultJsonFactory;
    }

    public function execute()
    {

        $resultRedirect = $this->resultRedirectFactory->create();
        $adviceApiClient = $this->getAdviceApiClient();
        $orderApiClient = $this->getOrderApiClient();
        $orderApiClient->setOrderId($this->getOrderNumber());
        $resp = json_decode($orderApiClient->get(), true);
        $resp['parcel'] = $resp['parcels'][0];
        try {
            $adviceApiClient->create($resp);
            $this->messageManager->addSuccessMessage(__('Order Bliskapaczka adviced'));
        } catch (\Exception $exception) {
            $this->messageManager->addError($exception->getMessage());
        }

        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }

    /**
     * @return Advice
     */
    protected function getAdviceApiClient()
    {
        $configuration = Configuration::fromStoreConfiguration();

        $apiClient = new Advice(
            $configuration->getApikey(),
            $configuration->getEnvironment()
        );

        return $apiClient;
    }

    /**
     * @return Order
     */
    protected function getOrderApiClient()
    {
        $configuration = Configuration::fromStoreConfiguration();
        $apiClient = new Order(
            $configuration->getApikey(),
            $configuration->getEnvironment()
        );

        return $apiClient;
    }

    /**
     * @return string
     */
    protected function getOrderNumber()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        $order = $this->_objectManager->get('Magento\Sales\Model\Order')->load($orderId);
        return $order->getNumber();
    }
}
