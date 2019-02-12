<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 07.01.19
 * Time: 14:08
 */

namespace Sendit\Bliskapaczka\Controller\Adminhtml\Cancel;

use Sendit\Bliskapaczka\Model\Api\CancelApiClient;
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
        $orderId = $this->getRequest()->getParam('order_id');
        $order = $this->_objectManager->get('Magento\Sales\Model\Order')->load($orderId);
        $conf = Configuration::fromStoreConfiguration();
        $cancel = CancelApiClient::fromConfiguration($conf);
        $cancel->setOrderId($order->getNumber());
        try {
            $cancel->cancel();
            $this->messageManager->addSuccessMessage(__('Order Bliskapaczka canceled'));
        } catch (\Exception $exception) {
            $this->messageManager->addError($exception->getMessage());
        }

        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
}
