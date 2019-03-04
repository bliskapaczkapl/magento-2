<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 07.01.19
 * Time: 14:08
 */

namespace Sendit\Bliskapaczka\Controller\Adminhtml\Update;

use Sendit\Bliskapaczka\Model\Api\Configuration;
use Bliskapaczka\ApiClient\Bliskapaczka\Order as BliskapaczkaOrder;
use Bliskapaczka\ApiClient\Bliskapaczka\Todoor as BliskapaczkaTodoor;

/**
 * Controller class for waybill action
 */
class Index extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    protected $order;

    /**
     * Construct method
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
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
        $this->order = $this->getOrder();
        $order = $this->getOrderApiClient();
        $order->setOrderId($this->order->getNumber());
        try {
//            $resp = json_decode($waybill->get());
//            $url = $resp[0]->url;
//            $resultRedirect->setUrl($url);
        } catch (\Exception $exception) {
            $this->messageManager->addError($exception->getMessage());
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        }


        return $resultRedirect;
    }

    /**
     * @return Magento\Sales\Model\Order
     */
    protected function getOrder()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        $order = $this->_objectManager->get('Magento\Sales\Model\Order')->load($orderId);
        return $order;
    }

    /**
     * @return BliskapaczkaOrder|BliskapaczkaTodoor
     */
    protected function getOrderApiClient()
    {
        $configuration = Configuration::fromStoreConfiguration();
        if ($this->order->getPosCode()) {
            $apiClient = new BliskapaczkaOrder(
                $configuration->getApikey(),
                $configuration->getEnvironment()
            );
        } else {
            $apiClient = new BliskapaczkaTodoor(
                $configuration->getApikey(),
                $configuration->getEnvironment()
            );
        }


        return $apiClient;
    }
}
