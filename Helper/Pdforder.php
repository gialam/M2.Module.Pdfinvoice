<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\PDFInvoice\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Sales\Model\OrderFactory;
use Magenest\PDFInvoice\Helper\Pdf;

/**
 * Class Pdforder
 *
 * @package Magenest\PDFInvoice\Helper
 */
class Pdforder extends AbstractHelper
{
    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderFactory;

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
        Pdf $pdf,
        OrderFactory $orderFactory,
        array $data = []
    ) {
        $this->orderFactory = $orderFactory;
        $this->pdf          = $pdf;
        parent::__construct($context);
    }


    /**
     * @param $orderId
     * @return bool|string
     * @throws \Zend_Pdf_Exception
     */
    public function getPrintOrder($orderId)
    {
        if ($orderId) {
            $pdf   = new \Zend_Pdf();
            $page  = new \Zend_Pdf_Page('595:842');
            $total[] = $page;
            $model = $this->orderFactory->create()->load($orderId);
            $fontEdit = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA_BOLD);
            $number = count($model->getAllItems());

            if ($number > 0) {
                $page = $this->pdf->printPdf($page, $model, 'order');
                $y    = 762;
                $page->setFont($fontEdit, 14)
                    ->drawText(__(' ORDER '), 20, 818, 'UTF-8');
                $page->setFont($fontEdit, 13);
                $page->drawText(__('Order Number : #').$model->getIncrementId(), 50, $y, 'UTF-8');
                $page->setFont($fontEdit, 11);
                $page->drawText(__('Date : ').$model->getUpdatedAt(), 50, ($y - 15), 'UTF-8');
            }


            $pdf->pages[] = $page;
            return $pdf->render();
        } else {
            return false;
        }
    }
}
