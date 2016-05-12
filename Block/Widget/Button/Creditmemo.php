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

class Creditmemo
{
    /**
     * @var Connector
     */
    protected $_connector;

    /**
     * Creditmemo constructor.
     * @param Connector $connector
     */
    public function __construct(
        Connector $connector
    ) {
    
        $this->_connector = $connector;
    }
    public function beforePushButtons(
        ToolbarContext $toolbar,
        \Magento\Framework\View\Element\AbstractBlock $context,
        \Magento\Backend\Block\Widget\Button\ButtonList $buttonList
    ) {
        if (!$context instanceof \Magento\Sales\Block\Adminhtml\Order\Creditmemo\View) {
            return [$context, $buttonList];
        }


        $buttonList->add(
            'print_credit_memo',
            [
                'label' => __('Print Custom PDF File'),
              'onclick' => 'setLocation(\'' . $context->getUrl('pdfinvoice/order/pdfcreditprint', ['creditmemo_id' => $context->getCreditmemo()->getId()]) . '\')',
                'class' => 'print_credit_memo'
            ]
        );
        $check = $this->_connector->getPrint();
        if ($check == 1) {
            $buttonList->remove('print');
        }

        return [$context, $buttonList];
    }
}
