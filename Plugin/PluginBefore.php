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
        if($this->_request->getFullActionName() == 'sales_order_view'){
            $buttonList->add(
                'update_bliska_paczka',
                ['label' => __('Aktualizuj Bliską paczkę'), 'onclick' => 'setLocation(window.location.href)', 'class' => 'reset'],
                -1
            );
            $buttonList->add(
                'waybill_bliska_paczka',
                ['label' => __('List przewozowy'), 'onclick' => 'setLocation(window.location.href)', 'class' => 'reset'],
                -1
            );
            $buttonList->add(
                'delete_bliska_paczka',
                ['label' => __('Anuluj Bliską paczkę'), 'onclick' => 'setLocation(window.location.href)', 'class' => 'reset'],
                -1
            );
        }

    }
}