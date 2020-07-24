<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 31.12.18
 * Time: 13:23
 */

namespace Sendit\Bliskapaczka\Plugin;

use Sendit\Bliskapaczka\Helper\Order as SenditOrderHelper;

/**
 * Buttons on order page for bliskapaczka
 */
/**
 * Class PluginBefore
 * @package Sendit\Bliskapaczka\Plugin
 */
class PluginBefore
{
   /**
    * @var OrderRepository
    */
    protected $orderRepository;

   /**
    * SaveToQuote constructor.
    * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
    * @param Sendit\Bliskapaczka\Helper\Order $senditOrderHelper
    */
    public function __construct(
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        SenditOrderHelper $senditOrderHelper
    ) {
        $this->orderRepository = $orderRepository;
        $this->senditOrderHelper = $senditOrderHelper;
    }
    /**
     * Add buttons
     * @param \Magento\Backend\Block\Widget\Button\Toolbar\Interceptor $subject
     * @param \Magento\Framework\View\Element\AbstractBlock $context
     * @param \Magento\Backend\Block\Widget\Button\ButtonList $buttonList
     */
    public function beforePushButtons(
        \Magento\Backend\Block\Widget\Button\Toolbar\Interceptor $subject,
        \Magento\Framework\View\Element\AbstractBlock $context,
        \Magento\Backend\Block\Widget\Button\ButtonList $buttonList
    ) {
        $this->request = $context->getRequest();
        $orderId = $this->request->getParam('order_id');

        $adviceUrl = $context->getUrl('advice/advice/', ['order_id' => $orderId]);
        $waybillUrl = $context->getUrl('waybill/waybill/', ['order_id' => $orderId]);
        $cancelUrl = $context->getUrl('cancel/cancel/', ['order_id' => $orderId]);
        $retryUrl = $context->getUrl('recreate/recreate/', ['order_id' => $orderId]);
        $updateUrl = $context->getUrl('update/update/', ['order_id' => $orderId]);
        $confirmPocztaUrl = $context->getUrl('confirm/confirm/', ['operator_name' => 'Poczta']);

        if ($this->request->getFullActionName() == 'sales_order_view') {
            $order = $this->orderRepository->get($orderId);

            if ($this->senditOrderHelper->canWaybill($order)) {
                $buttonList->add(
                    'waybill_bliska_paczka',
                    [
                        'label' => __('List przewozowy'),
                        'onclick' => sprintf("setLocation('%s')", $waybillUrl),
                        'class' => 'reset'
                    ],
                    -1
                );
            }
            if ($this->senditOrderHelper->canCancel($order)) {
                $buttonList->add(
                    'delete_bliska_paczka',
                    ['label' => __('Anuluj Bliską paczkę'),
                        'onclick' => sprintf("setLocation('%s')", $cancelUrl), 'class' => 'reset'
                    ],
                    -1
                );
            }
            if ($this->senditOrderHelper->canAdvice($order)) {
                $buttonList->add(
                    'advice_bliska_paczka',
                    ['label' => __('Awizuj Bliską paczkę'),
                        'onclick' => sprintf("setLocation('%s')", $adviceUrl),
                        'class' => 'advice'
                    ],
                    -1
                );
            }
            if ($this->senditOrderHelper->canRetry($order)) {
                $buttonList->add(
                    'retry_bliska_paczka',
                    ['label' => __('Ponów Bliską paczkę'),
                        'onclick' => sprintf("setLocation('%s')", $retryUrl),
                        'class' => 'reset'
                    ],
                    -1
                );
            }
            $buttonList->add(
                'update_bliska_paczka',
                ['label' => __('Aktualizuj Bliską paczkę'),
                    'onclick' => sprintf("setLocation('%s')", $updateUrl),
                    'class' => 'update'
                ],
                -1
            );
            $buttonList->add(
                'confirm_poczta',
                ['label' => __('Wyczyść bufor Poczty Polskiej'),
                    'onclick' => sprintf("setLocation('%s')", $confirmPocztaUrl),
                    'class' => 'confirm_poczta'
                ],
                -1
            );
        }
    }
}
