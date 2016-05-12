<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * @category  Magenest
 */
namespace Magenest\PDFInvoice\Block\Widget\Button;

use Magento\Backend\Block\Widget\Button\Toolbar as ToolbarContext;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Backend\Block\Widget\Button\ButtonList;
use Magenest\PDFInvoice\Model\Connector;

class Invoice
{
    /**
     * @var Connector
     */
    protected $_connector;
    /**
     * @param ToolbarContext $toolbar
     * @param AbstractBlock $context
     * @param ButtonList $buttonList
     * @return array
     */
    public function __construct(
        Connector $connector
    )
    {
        $this->_connector = $connector;
    }

    /**
     * @param ToolbarContext $toolbar
     * @param AbstractBlock $context
     * @param ButtonList $buttonList
     * @return array
     */
    public function beforePushButtons(
        ToolbarContext $toolbar,

        \Magento\Framework\View\Element\AbstractBlock $context,
        \Magento\Backend\Block\Widget\Button\ButtonList $buttonList
    ) {
        if (!$context instanceof \Magento\Sales\Block\Adminhtml\Order\Invoice\View) {
            return [$context, $buttonList];
        }

        $buttonList->add('print_invoice',
            [
                'label' => __('Print Custom PDF File'),
                'onclick' => 'setLocation(\'' . $context->getUrl('pdfinvoice/order/pdfinvoiceprint', ['invoice_id' => $context->getInvoice()->getId()]) . '\')',
                'class' => 'print_invoice'
            ]
        );
        $check = $this->_connector->getPrint();
        if($check == 1) {
            $buttonList->remove('print');
        }

        return [$context, $buttonList];
    }
}