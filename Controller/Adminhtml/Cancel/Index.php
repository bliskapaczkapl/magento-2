<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 07.01.19
 * Time: 14:08
 */

namespace Sendit\Bliskapaczka\Controller\Adminhtml\Cancel;

use Sendit\Bliskapaczka\Model\Api\Configuration;
use Bliskapaczka\ApiClient\Bliskapaczka\Order\Cancel;

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
        $cancel = $this->getCancelApiClient();
        $cancel->setOrderId($this->getOrderNumber());
        try {
            $cancel->cancel();
            $this->messageManager->addSuccessMessage(__('Order Bliskapaczka canceled'));
        } catch (\Exception $exception) {
            $this->messageManager->addError($exception->getMessage());
        }

        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
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
     * @return Cancel
     */
    protected function getCancelApiClient()
    {
        $configuration = Configuration::fromStoreConfiguration();
        $apiClient = new Cancel(
            $configuration->getApikey(),
            $configuration->getEnvironment()
        );

        return $apiClient;
    }
}
