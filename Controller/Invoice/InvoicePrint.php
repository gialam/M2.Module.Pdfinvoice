<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.

 */
namespace Magenest\PDFInvoice\Controller\Invoice;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\FrameWork\App\Action\Action;
use Magenest\PDFInvoice\Helper\Invoice\Pdfinvoice;

/**
 * Class PdfinvoicePrintFrontend
 * @package Magenest\PDFInvoice\Controller\Invoice
 */
class InvoicePrint extends Action
{

    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @var DateTime
     */
    protected $dateTime;


    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Pdfinvoice
     */
    protected $_dataTemplate;

    /**
     * PdfinvoicePrintFrontend constructor.
     * @param Context $context
     * @param DateTime $dateTime
     * @param FileFactory $fileFactory
     * @param CustomerSession $customerSession
     * @param PageFactory $resultPageFactory
     * @param Pdfinvoice $dataTemplate
     */
    public function __construct(
        Context $context,
        DateTime $dateTime,
        FileFactory $fileFactory,
        CustomerSession $customerSession,
        PageFactory $resultPageFactory,
        Pdfinvoice $dataTemplate
    ) {
        $this->fileFactory = $fileFactory;
        $this->dateTime = $dateTime;
        $this->_customerSession = $customerSession;
        $this->resultPageFactory = $resultPageFactory;
        $this->_dataTemplate = $dataTemplate;

        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface
     * @throws \Exception
     */
    public function execute()
    {
        $invoiceId = $this->getRequest()->getParam('invoice_id');
        if ($invoiceId) {
            return $this->fileFactory->create(
                sprintf('invoice%s.pdf', $this->dateTime->date('Y-m-d_H-i-s')),
                $this->_dataTemplate->getPrintInvoice($invoiceId),
                DirectoryList::VAR_DIR,
                'application/pdf'
            );
        }
    }
    /* @return bool
     */
    protected function _isAllowed()
    {
        return true;
    }
}
