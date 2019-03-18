<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 12.03.19
 * Time: 14:14
 */

namespace Sendit\Bliskapaczka\Cron;

use \Psr\Log\LoggerInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\OrderManagementInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Sendit\Bliskapaczka\Helper\Data;
use Sendit\Bliskapaczka\Model\Api\Configuration;
use Bliskapaczka\ApiClient\Bliskapaczka\Order\Advice as BliskapaczkaOrderAdvice;
use Bliskapaczka\ApiClient\Bliskapaczka\Order\Todoor as BliskapaczkaOrderTodoor;

/**
 * Class Advice
 * @package Sendit\Bliskapaczka\Cron
 */
class Advice
{

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var FilterGroup
     */
    private $filterGroup;

    /**
     * @var OrderManagementInterface
     */
    private $orderManagement;
    /** @var LoggerInterface */
    private $logger;

    /**
     * Advice constructor.
     * @param OrderRepositoryInterface $orderRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     * @param FilterGroup $filterGroup
     * @param OrderManagementInterface $orderManagement
     * @param LoggerInterface $logger
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        FilterGroup $filterGroup,
        OrderManagementInterface $orderManagement,
        LoggerInterface $logger
    ) {
        $this->orderRepository       = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder         = $filterBuilder;
        $this->filterGroup           = $filterGroup;
        $this->orderManagement       = $orderManagement;
        $this->logger = $logger;

        $this->todoor = $this->_objectManager->get('Sendit\Bliskapaczka\Model\Mapper\Todoor');
        $this->order = $this->_objectManager->get('Sendit\Bliskapaczka\Model\Mapper\Order');
    }


    /**
     * Execute method
     */
    public function execute()
    {
        foreach ($this->getOrders() as $order) {
            if ($order->getPosCode()) {
                $mapper = $this->order;
            } else {
                $mapper = $this->todoor;
            }
            $data = $mapper->getData($order);
            $apiClient = $this->getAdviceApiClientByOrder($order);
            $apiClient->setOrderId($order->getNumber());
            try {
                $apiClient->create($data);
                $order->setData('is_error', false);
                $this->logger->info(sprintf('Updated order %s - advice ', $order->getNumber()));
            } catch (\Exception $exception) {
                $order->setData('is_error', true);
                $order->setData('attempts_count', $order->getData('attempts_count') + 1);
                $this->logger->info(sprintf('Error %s. Order %s', $exception->getMessage(), $order->getNumber()));
            }
        }
    }

    /**
     * @return \Magento\Sales\Api\Data\OrderSearchResultInterface
     */
    protected function getOrders()
    {
        $filterGroupStatus = $this->filterGroup;
        $filterStatus    = $this->filterBuilder
            ->setField('is_error')
            ->setConditionType('is')
            ->setValue(true)
            ->create();
        $filterGroupStatus->setFilters([$filterStatus]);
        $searchCriteria = $this->searchCriteriaBuilder->setFilterGroups([$filterGroupStatus]);
        return $this->orderRepository->getList($searchCriteria->create());
    }

    /**
     * @param object $order
     * @return BliskapaczkaOrderAdvice|BliskapaczkaTodoorAdvice
     */
    protected function getAdviceApiClientByOrder($order)
    {
        $configuration = Configuration::fromStoreConfiguration();
        if ($order->getPosCode()) {
            $apiClient = new BliskaPaczkaOrderAdvice(
                $configuration->getApikey(),
                $configuration->getEnvironment()
            );
        } else {
            $apiClient = new BliskapaczkaTodoorAdvice(
                $configuration->getApikey(),
                $configuration->getEnvironment()
            );
        }
        return $apiClient;
    }
}
