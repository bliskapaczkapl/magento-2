<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 19.12.18
 * Time: 10:02
 */

namespace Sendit\Bliskapaczka\Block\Adminhtml\OrderEdit\Tab;

use Sendit\Bliskapaczka\Model\Api\Configuration;
use Bliskapaczka\ApiClient\Bliskapaczka\Order as BliskapaczkaOrder;

/**
 * Class View
 * @package Sendit\Bliskapaczka\Block\Adminhtml\OrderEdit\Tab
 */
class View extends \Magento\Backend\Block\Template implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var string
     */
    protected $_template = 'tab/view/bliskapaczka_order_info.phtml';

    /**
     * @var array|mixed
     */
    protected $_dataFromApi = [];

    /** @var string */
    protected $_trackingUrl;
    /**
     * View constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {

        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);

        try {
            $configuration = Configuration::fromStoreConfiguration();
            $apiClient = new BliskapaczkaOrder(
                $configuration->getApikey(),
                $configuration->getEnvironment()
            );
            $apiClient->setOrderId($this->getOrder()->getNumber());
            $resp = $apiClient->get();

            $this->_dataFromApi = json_decode($resp, true);
            $this->setUrlToTrackingByEnv($configuration->getEnvironment());
        } catch (\Exception $e) {
        }
    }

    /**
     * Retrieve order model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->_coreRegistry->registry('current_order');
    }
    /**
     * Retrieve order model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrderId()
    {
        return $this->getOrder()->getEntityId();
    }

    /**
     * Retrieve order increment id
     *
     * @return string
     */
    public function getOrderIncrementId()
    {
        return $this->getOrder()->getIncrementId();
    }
    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Bliska Paczka');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Bliska Paczka');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * @return array|mixed
     */
    public function getDataFromApi()
    {
        return $this->_dataFromApi;
    }

    /**
     * @return mixed
     */
    public function getTrackingLink()
    {
        if (isset($this->getDataFromApi()['trackingNumber'])) {
            return $this->_trackingUrl . $this->getDataFromApi()['trackingNumber'];
        }
    }

    /**
     * @param string $env
     */
    protected function setUrlToTrackingByEnv(string $env)
    {
        if ($env === 'test') {
            $this->_trackingUrl = 'https://sandbox-bliskapaczka.pl/sledzenie/';
        } else {
            $this->_trackingUrl = 'https://bliskapaczka.pl/sledzenie/';
        }
    }
}
