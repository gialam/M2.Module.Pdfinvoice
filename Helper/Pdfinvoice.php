<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\PDFInvoice\Helper;


use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;



/**
 * Class Pdfinvoice
 *
 * @package Magenest\PDFInvoice\Helper
 */
class Pdfinvoice extends AbstractHelper
{

    protected $pdf;

    /**
     * @var \Magento\Sales\Model\Order\Invoice
     */
    protected $_invoiceCollection;


    /**
     * Pdfinvoice constructor.
     * @param Context $context
     * @param \Magenest\PDFInvoice\Helper\Pdf $pdf
     * @param \Magento\Sales\Model\Order\Invoice $invoiceCollection
     * @param \Magenest\PDFInvoice\Helper\Pdf $pdf
     * @param array $data
     */
    public function __construct(
        Context $context,
        \Magenest\PDFInvoice\Helper\Pdf $pdf,
        \Magento\Sales\Model\Order\Invoice $invoiceCollection,
//        \Magenest\PDFInvoice\Helper\Pdf $pdf,
        array $data = []
    ) {

        $this->_invoiceCollection    = $invoiceCollection;
        $this->pdf          = $pdf;

        parent::__construct($context);

    }//end __construct()


    /**
     * @param $invoiceId
     * @return bool|string
     * @throws \Zend_Pdf_Exception
     */
    public function getPrintInvoice($invoiceId)
    {
        if ($invoiceId) {
            $pdf   = new \Zend_Pdf();
            $page  = new \Zend_Pdf_Page('595:842');
            $total[] = $page;
            $model = $this->_invoiceCollection->load($invoiceId);
            $fontEdit = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA_BOLD);;
            $number = count($model->getOrder()->getAllItems());
            if ($number > 0) {
                $page  = $this->pdf->printPdf($page, 'invoice');
                $invoice = 762;
                $page->setFont($fontEdit, 14)
                    ->drawText(' INVOICE ', 20, 818, 'UTF-8');
                $page->setFont($fontEdit, 11);
                $page->setFont($fontEdit, 13);
                $page->drawText('Invoice Number : #'.$model->getIncrementId(), 50, $invoice, 'UTF-8');
                $page->setFont($fontEdit, 11);
                $page->drawText('Date : '.$model->getUpdatedAt(), 50, ($invoice - 15), 'UTF-8');

            }
            $pdf->pages[] = $page;

            return $pdf->render();
        } else {
            return false;
        }

    }


}
