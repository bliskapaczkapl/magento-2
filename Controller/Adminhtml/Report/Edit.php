<?php
namespace Sendit\Bliskapaczka\Controller\Adminhtml\Report;

/**
 * Controller class for report action
 */
class Edit extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;

    /**
     * Construct method
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    ) {
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    /**
     * Get report
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_pageFactory->create();
        $resultPage->setActiveMenu('Sendit_Bliskapaczka::raports');
        $resultPage->getConfig()->getTitle()->prepend(__('Bliskapaczka raports'));

        return $resultPage;
    }
}
