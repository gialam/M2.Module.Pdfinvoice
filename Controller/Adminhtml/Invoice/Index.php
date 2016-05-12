<?php
/**
 * Created by PhpStorm.
 */
namespace   Magenest\PDFInvoice\Controller\Adminhtml\Invoice;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 * @package Magenest\PDFInvoice\Controller\Adminhtml\Invoice
 */
class Index extends \Magento\Backend\App\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context     $context,
        PageFactory     $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    public function execute()
    {
        $resultPage     = $this->resultPageFactory->create();

        $resultPage->setActiveMenu('Magenest_PDFInvoice::invoice');
        $resultPage->addBreadcrumb(__('PDF Invoice'), __('PDF Invoice'));
        $resultPage->addBreadcrumb(__('Manage PDF Invoice'), __('Manage PDF Invoice'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage PDF Invoice'));

        return  $resultPage;
    }

    protected function _isAllowed()
    {
        return  true;

    }
}
