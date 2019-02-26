<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 31.12.18
 * Time: 13:23
 */

namespace Sendit\Bliskapaczka\Plugin;

class PluginBefore
{
    public function beforePushButtons(
        \Magento\Backend\Block\Widget\Button\Toolbar\Interceptor $subject,
        \Magento\Framework\View\Element\AbstractBlock $context,
        \Magento\Backend\Block\Widget\Button\ButtonList $buttonList
    ) {

        $this->_request = $context->getRequest();
        $orderId = $this->_request->getParam('order_id');
        $adviceUrl = $context->getUrl('advice/advice/', ['order_id' => $orderId]);
        $waybillUrl = $context->getUrl('waybill/waybill/', ['order_id' => $orderId]);
        $cancelUrl = $context->getUrl('cancel/cancel/', ['order_id' => $orderId]);
        $retryUrl = $context->getUrl('recreate/recreate/', ['order_id' => $orderId]);
        if ($this->_request->getFullActionName() == 'sales_order_view') {
            $buttonList->add(
                'waybill_bliska_paczka',
                [
                    'label' => __('List przewozowy'),
                    'onclick' => sprintf("setLocation('%s')", $waybillUrl),
                    'class' => 'reset'
                ],
                -1
            );
            $buttonList->add(
                'delete_bliska_paczka',
                ['label' => __('Anuluj Bliską paczkę'),
                    'onclick' => sprintf("setLocation('%s')", $cancelUrl), 'class' => 'reset'
                ],
                -1
            );
            $buttonList->add(
                'advice_bliska_paczka',
                ['label' => __('Awizuj Bliską paczkę'),
                    'onclick' => sprintf("setLocation('%s')", $adviceUrl),
                    'class' => 'reset'
                ],
                -1
            );
            $buttonList->add(
                'retry_bliska_paczka',
                ['label' => __('Ponów Bliską paczkę'),
                    'onclick' => sprintf("setLocation('%s')", $retryUrl),
                    'class' => 'reset'
                ],
                -1
            );
        }
    }
}