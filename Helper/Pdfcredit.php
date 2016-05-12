<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\PDFInvoice\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

/**
 * Class Pdforder
 *
 * @package Magenest\PDFInvoice\Helper
 */
class Pdfcredit extends AbstractHelper
{
    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_creditmemoCollection;

    /**
     * @var
     */
    protected $pdf;

    /**
     * @param Context $context
     * @param Pdf $pdf
     * @param OrderFactory $orderFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        \Magenest\PDFInvoice\Helper\Pdf $pdf,
        \Magento\Sales\Model\Order\Creditmemo $creditmemoCollection,
        array $data = []
    ) {
        $this->_creditmemoCollection    = $creditmemoCollection;
        $this->pdf          = $pdf;
        parent::__construct($context);
    }


    /**
     * @param $creditmemoId
     * @return bool|string
     * @throws \Zend_Pdf_Exception
     */
    public function getPrintCreditmemo($creditmemoId)
    {
        if ($creditmemoId) {
            $pdf   = new \Zend_Pdf();
            $page  = new \Zend_Pdf_Page('595:842');
            $total[] = $page;
            $model = $this->_creditmemoCollection->load($creditmemoId);
            $fontEdit = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA_BOLD);
            $number = count($model->getAllItems()) ;

            if ($number > 0) {
                $page = $this->pdf->printPdf($page, $model, 'creditmemo');
                $credit = 762;
                $page->setFont($fontEdit, 14)
                    ->drawText(' CREDIT MEMO ', 20, 818, 'UTF-8');
                $page->setFont($fontEdit, 11);
                $page->setFont($fontEdit, 13);
                $page->drawText('Credit memo Number : #'.$model->getIncrementId(), 50, $credit, 'UTF-8');
                $page->setFont($fontEdit, 11);
                $page->drawText('Date : '.$model->getUpdatedAt(), 50, ($credit - 15), 'UTF-8');

            }
            $pdf->pages[] = $page;
            return $pdf->render();
        } else {
            return false;
        }
    }
}
