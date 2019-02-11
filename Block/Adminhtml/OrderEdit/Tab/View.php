<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 19.12.18
 * Time: 10:02
 */

namespace Sendit\Bliskapaczka\Block\Adminhtml\OrderEdit\Tab;

use Sendit\Bliskapaczka\Model\Api\Configuration;
use Sendit\Bliskapaczka\Model\Api\OrderApiClient;

class View extends \Magento\Backend\Block\Template implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    protected $_template = 'tab/view/my_order_info.phtml';

    protected $_dataFromApi = [];
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
        $conf = Configuration::fromStoreConfiguration();
        $orderApiClient = OrderApiClient::fromConfiguration($conf);
        $orderApiClient->setOrderId($this->getOrder()->getNumber());
        $resp = $orderApiClient->get();
        $this->_dataFromApi = json_decode($resp, true);
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
        return __('Biska Paczka');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Biska Paczka');
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

    public function getDataFromApi()
    {
        return $this->_dataFromApi;
    }
}
