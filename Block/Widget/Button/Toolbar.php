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

class Toolbar
{
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
        if (!$context instanceof \Magento\Sales\Block\Adminhtml\Order\View) {
            return [$context, $buttonList];
        }

        $buttonList->update('order_edit', 'class', 'edit');
        $buttonList->update('order_invoice', 'class', 'invoice primary');
        $buttonList->update('order_invoice', 'sort_order', (count($buttonList->getItems()) + 1) * 10);
        $buttonList->add(
            'print_order',
            [
                'label' => __('Print Custom PDF File'),
                'onclick' => 'setLocation(\'' . $context->getUrl('pdfinvoice/*/pdforderprint') . '\')',
                'class' => 'print_order'
            ]
        );


        return [$context, $buttonList];
    }
}
