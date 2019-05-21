<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 06.03.19
 * Time: 09:51
 */

namespace Sendit\Bliskapaczka\Cron;

use \Psr\Log\LoggerInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\OrderManagementInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Sendit\Bliskapaczka\Helper\Data;
use Sendit\Bliskapaczka\Model\Api\Configuration;
use Bliskapaczka\ApiClient\Bliskapaczka\Order as BliskapaczkaOrder;
use Bliskapaczka\ApiClient\Bliskapaczka\Todoor as BliskapaczkaTodoor;

/**
 * Class Order
 * @package Sendit\Bliskapaczka\Cron
 */
class Order
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
     * CancelOrderPending constructor.
     *
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
        LoggerInterface  $logger
    ) {
        $this->orderRepository       = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder         = $filterBuilder;
        $this->filterGroup           = $filterGroup;
        $this->orderManagement       = $orderManagement;
        $this->logger = $logger;
    }

    /**
     * executeFast method
     */
    public function executeFastStatuses()
    {
        try {
            $this->execute(Data::FAST_STATUSES);
            $this->logger->info('Fast update is done');
        } catch (\Exception $exception) {
            $this->logger->info(sprintf("%s %s", "We have a problem", $exception->getMessage()));
        }
    }

    /**
     * executeSlow method
     */
    public function executeSlowStatuses()
    {
        try {
            $this->execute(Data::SLOW_STATUSES);
            $this->logger->info('Fast update is done');
        } catch (\Exception $exception) {
            $this->logger->info(sprintf("%s %s", "We have a problem", $exception->getMessage()));
        }
    }


    /**
     * @param object $order
     * @return BliskapaczkaOrder|BliskapaczkaTodoor
     */
    protected function getOrderApiClientByOrder($order)
    {
        $configuration = Configuration::fromStoreConfiguration();
        if ($order->getPosCode()) {
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

    /**
     * @param array $statuses
     * @throws \Bliskapaczka\ApiClient\Exception
     */
    protected function execute(array $statuses)
    {
        $filterGroupStatus = $this->filterGroup;
        $filterStatus    = $this->filterBuilder
            ->setField('bliskapaczka_status')
            ->setConditionType('in')
            ->setValue($statuses)
            ->create();
        $filterGroupStatus->setFilters([$filterStatus]);
        $searchCriteria = $this->searchCriteriaBuilder->setFilterGroups([$filterGroupStatus]);
        $searchResults  = $this->orderRepository->getList($searchCriteria->create());

        /** @var Order $order */
        foreach ($searchResults->getItems() as $order) {
            try {
                $orderApi = $this->getOrderApiClientByOrder($order);
                $orderApi->setOrderId($order->getNumber());

                $data = json_decode($orderApi->get());
                $order->setData("tracking_number", $data->trackingNumber);
                $order->setData("advice_date", $data->adviceDate);
                $order->setData("bliskapaczka_status", $data->status);
                $order->save();
            } catch (\Exception $e) {
            }
        }
    }
}
