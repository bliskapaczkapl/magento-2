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
use Bliskapaczka\ApiClient\Bliskapaczka\Todoor\Advice as BliskapaczkaTodoorAdvice;
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
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
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
        $resultRedirect = $this->resultRedirectFactory->create();
        try {
            $orderId = $this->getRequest()->getParam('order_id');
            $order = $this->_objectManager->get('Magento\Sales\Model\Order')->load($orderId);
        } catch (\Exception $exception) {
            $this->messageManager->addError($exception->getMessage());
            return $resultRedirect;
        }

        try {
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

            if (!is_a($response, 'stdClass')) {
                throw new \Exception("Bliskapaczka API response is invalid. Try again.", 1);
            }

            $order->setData("tracking_number", $response->trackingNumber);
            $order->setData("advice_date", $response->adviceDate);
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
            $order->setData('is_error', true);
            $order->setData('attempts_count', $order->getData('attempts_count') + 1);
        }

        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
}
