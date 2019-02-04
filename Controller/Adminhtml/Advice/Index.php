<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 07.01.19
 * Time: 14:08
 */

namespace Sendit\Bliskapaczka\Controller\Adminhtml\Advice;


use Sendit\Bliskapaczka\Model\Api\AdviceApiClient;
use Sendit\Bliskapaczka\Model\Api\OrderApiClient;
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

    )
    {
        parent::__construct($context);
        $this->resultFactory = $resultJsonFactory;
    }

    public function execute()
    {

        $resultRedirect = $this->resultRedirectFactory->create();
        $orderId = $this->getRequest()->getParam('order_id');
        $order = $this->_objectManager->get('Magento\Sales\Model\Order')->load($orderId);
        $conf = Configuration::fromStoreConfiguration();
        $adviceApiClient = AdviceApiClient::fromConfiguration($conf);
        $orderApiClient = OrderApiClient::fromConfiguration($conf);
        $orderApiClient->setOrderId($order->getNumber());
        $resp = json_decode($orderApiClient->get(), true);
        $resp['parcel'] = $resp['parcels'][0];
        try {
            $adviceResp = $adviceApiClient->create($resp);
            $this->messageManager->addSuccessMessage(__('Order Bliskapaczka adviced'));
        } catch (\Exception $exception) {
            $this->messageManager->addError($exception->getMessage());
        }

        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;

    }
}