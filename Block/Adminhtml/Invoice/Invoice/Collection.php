<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * @category  Magenest
 */
namespace Magenest\PDFInvoice\Block\Adminhtml\Invoice\Invoice;

use Magento\Catalog\Api\ProductRepositoryInterface;

/**
 * Product View block
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */

class Collection extends \Magento\Catalog\Block\Product\View
{
    const CONFIG_TEMPLATE = 'invoice_setting/pdfinvoice_label/pdfinvoice_set_template';

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory
     */
    protected $itemCollection;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magenest\PDFInvoice\Model\ResourceModel\Invoice\Collection
     */
    protected $_invoiceCollection;

    /**
     * @var
     */
    protected $_configFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context     $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magenest\PDFInvoice\Model\ResourceModel\Invoice\Collection $invoiceCollection,
        \Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory $itemCollectionFactory,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->_invoiceCollection   =   $invoiceCollection;
        $this->itemCollection = $itemCollectionFactory;
        parent::__construct($context, $backendHelper, $data);
    }
    public function getData()
    {
        $data = $this->_coreRegistry->registry('invoice');
        return $data;
    }

    public function getOrder()
    {
        $orderId = $this->getRequest()->getParams();
        $collectionItem = $this->itemCollection->create()->addAttributeToFilter('order_id', $orderId['order_id']);
        $item = $collectionItem->getData();
        return $item;
    }
}
