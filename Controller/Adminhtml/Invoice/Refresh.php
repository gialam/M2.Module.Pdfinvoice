<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\PDFInvoice\Controller\Adminhtml\Invoice;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Refresh
 * @package Magenest\PDFInvoice\Controller\Adminhtml\Invoice
 */
class Refresh extends \Magento\Backend\App\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magenest\Salesforce\Model\Connector
     */
    protected $_connector;

    /**
     * Refresh constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param \Magenest\PDFInvoice\Model\Connector $connector
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magenest\PDFInvoice\Model\Connector $connector

    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_connector = $connector;
    }

    /**
     * execute
     */
    public function execute()
    {
        $reponse = $this->_connector->getAccessToken();

        if (!empty($reponse['access_token'])) {
            $this->messageManager->addSuccess('Refesh success !');
            $this->getResponse()->setRedirect($this->_redirect->getRedirectUrl($this->getUrl('*')));
            return;
        } else {
            $this->messageManager->addError('Can\'t refesh , please check in configuration!');
        }

        $this->_redirect('adminhtml/*/');
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_PDFInvoice::refresh');
    }
}
