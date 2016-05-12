<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\PDFInvoice\Block\Invoice;

/**
 * Class PrintPdf
 * @package Magenest\PDFInvoice\Block\Invoice
 */
class PrintPdf extends \Magento\Sales\Block\Order\Info\Buttons
{
    /**
     * @var \Magento\Sales\Model\Order\InvoiceFactory
     */
    protected $_invoiceFactory;

    /**
     * @var
     */
    protected $_creditmemoFactory;

    /**
     * @var
     */
    protected $_connector;

    /**
     * PrintPdf constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \Magento\Sales\Model\Order\InvoiceFactory $invoiceFactory
     * @param \Magento\Sales\Model\Order\CreditmemoFactory $creditmemoFactory
     * @param \Magenest\PDFInvoice\Model\Connector $connector
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Sales\Model\Order\InvoiceFactory $invoiceFactory,
        \Magento\Sales\Model\Order\CreditmemoFactory $creditmemoFactory,
        \Magenest\PDFInvoice\Model\Connector $connector,
        array $data = []
    ) {

        parent::__construct($context, $registry, $httpContext, $data);
        $this->_invoiceFactory = $invoiceFactory;
        $this->_creditmemoFactory = $creditmemoFactory;
        $this->_connector = $connector;
    }

    /**
     * @param $orderId
     * @return string
     */
    public function getPrintInvoiceUrl($orderId)
    {
        $model = $this->_invoiceFactory->create()->load($orderId, 'order_id');
        return $this->getUrl('pdfinvoice/invoice/invoiceprint', ['invoice_id'=> $model->getId()]);
    }

    /**
     * @return mixed
     */
    public function getEnablePrint()
    {
        $enableprint = $this->_connector->getPrintFrontend();
        return $enableprint;
    }
}
