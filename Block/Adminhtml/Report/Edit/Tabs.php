<?php

namespace Sendit\Bliskapaczka\Block\Adminhtml\Report\Edit;

use Magento\Backend\Block\Widget\Tabs as WidgetTabs;

class Tabs extends WidgetTabs
{
    /**
     * Class constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('report_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Report'));
    }

    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'report_info',
            [
                'label' => __('Report'),
                'title' => __('Report'),
                'content' => $this->getLayout()->createBlock(
                    'Sendit\Bliskapaczka\Block\Adminhtml\Report\Edit\Tab\Info'
                )->toHtml(),
                'active' => true
            ]
        );

        return parent::_beforeToHtml();
    }
}
