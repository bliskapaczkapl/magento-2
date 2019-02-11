<?php
namespace Sendit\Bliskapaczka\Controller\Adminhtml\Report;

class Edit extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory)
    {
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_pageFactory->create();
        $resultPage->setActiveMenu('Sendit_Bliskapaczka::raports');
        $resultPage->getConfig()->getTitle()->prepend(__('Simple News'));

        return $resultPage;
    }

}

  