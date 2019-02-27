<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 07.01.19
 * Time: 14:08
 */

namespace Sendit\Bliskapaczka\Controller\Adminhtml\Update;

use Sendit\Bliskapaczka\Model\Api\Configuration;
use Bliskapaczka\ApiClient\Bliskapaczka\Order\Waybill;

/**
 * Controller class for waybill action
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
    }

    /**
     * Get waybill
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $waybill = $this->getWaybillApiClient();
        $waybill->setOrderId($this->getOrderNumber());
        try {
            $resp = json_decode($waybill->get());
            $url = $resp[0]->url;
            $resultRedirect->setUrl($url);
        } catch (\Exception $exception) {
            $this->messageManager->addError($exception->getMessage());
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        }


        return $resultRedirect;
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

    /**
     * @return Waybill
     */
    protected function getWaybillApiClient()
    {
        $configuration = Configuration::fromStoreConfiguration();
        $apiClient = new Waybill(
            $configuration->getApikey(),
            $configuration->getEnvironment()
        );

        return $apiClient;
    }
}
