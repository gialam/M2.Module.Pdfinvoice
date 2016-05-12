<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 *
 * Magenest_Ticket extension
 * NOTICE OF LICENSE
 *
 * @category  Magenest
 * @package   Magenest_Ticket
 * @author ThaoPV <thaopw@gmail.com>
 */
namespace Magenest\PDFInvoice\Controller\Invoice;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Action;
use Magenest\PDFInvoice\Helper\Invoice\Pdforder;

/**
 * Class PdforderPrintFrontend
 * @package Magenest\PDFInvoice\Controller\Invoice
 */
class OrderPrint extends Action
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
     * @var Pdforder
     */
    protected $_dataTemplate;

    /**
     * PdforderPrintFrontend constructor.
     * @param Context $context
     * @param DateTime $dateTime
     * @param FileFactory $fileFactory
     * @param CustomerSession $customerSession
     * @param PageFactory $resultPageFactory
     * @param Pdforder $dataTemplate
     */
    public function __construct(
        Context $context,
        DateTime $dateTime,
        FileFactory $fileFactory,
        CustomerSession $customerSession,
        PageFactory $resultPageFactory,
        Pdforder $dataTemplate
    ) {
        $this->fileFactory = $fileFactory;
        $this->dateTime = $dateTime;
        $this->_customerSession = $customerSession;
        $this->resultPageFactory = $resultPageFactory;
        $this->_dataTemplate = $dataTemplate;

        parent::__construct($context);
    }

    /**
     * @return $this|\Magento\Framework\App\ResponseInterface|\Magento\Framework\View\Result\Page
     * @throws \Exception
     * @throws \Zend_Pdf_Exception
     */
    public function execute()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        if ($orderId) {
            return $this->fileFactory->create(
                sprintf('order%s.pdf', $this->dateTime->date('Y-m-d_H-i-s')),
                $this->_dataTemplate->getPrintOrder($orderId),
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
