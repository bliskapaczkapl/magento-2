<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 30.01.19
 * Time: 10:41
 */

namespace Sendit\Bliskapaczka\Block\Adminhtml\Report\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;
use Sendit\Bliskapaczka\Model\Api\ReportApiClient;

class Info extends Generic implements TabInterface
{
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;

    /**
     * @var \Tutorial\SimpleNews\Model\Config\Status
     */
    protected $_newsStatus;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Config $wysiwygConfig
     * @param Status $newsStatus
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Config $wysiwygConfig,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form fields
     *
     * @return \Magento\Backend\Block\Widget\Form
     */
    protected function _prepareForm()
    {

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('report_');
        $form->setFieldNameSuffix('report');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Report')]
        );
        foreach (ReportApiClient::OPERATORS as $operator => $name) {
            $fieldset->addField(
                $name,
                'label',
                [
                    'name' => $name,
                    'label' => $name
                ]
            );
            $fieldset->addField(
                $operator . 'to',
                'date',
                [
                    'name'        => $operator . 'to',
                    'label'    => __('To'),
                    'required'     => false,
                    'format' => 'Y-m-d'
                ]
            );
            $fieldset->addField(
                $operator . 'from',
                'date',
                [
                    'name'        => $operator . 'from',
                    'label'    => __('From'),
                    'required'     => false,
                    'format' => 'Y-m-d'
                ]
            );
        }


        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('News Info');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('News Info');
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
}
