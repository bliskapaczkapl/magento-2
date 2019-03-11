<?php
namespace Sendit\Bliskapaczka\Controller\Adminhtml\Confirm;

use Sendit\Bliskapaczka\Model\Api\Configuration;
use Bliskapaczka\ApiClient\Bliskapaczka\Order\Confirm;

/**
 * Controller class for orders action
 */
class Index extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Load the page defined in view/adminhtml/layout/exampleadminnewpage_helloworld_index.xml
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {

        $resultRedirect = $this->resultRedirectFactory->create();
        $confirm = $this->getConfirmApiClient();
        $confirm->setOperator($this->getRequest()->getParam('operator_name'));
        try {
            $confirm->confirm();
            $this->messageManager->addSuccessMessage(__('Cleaned Poczta Polska bufor'));
        } catch (\Exception $exception) {
            $this->messageManager->addError($exception->getMessage());
        }

        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }

    /**
     * @return Confirm
     */
    protected function getConfirmApiClient()
    {
        $configuration = Configuration::fromStoreConfiguration();
        $apiClient = new Confirm(
            $configuration->getApikey(),
            $configuration->getEnvironment()
        );

        return $apiClient;
    }
}
