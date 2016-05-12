<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\PDFInvoice\Block\Invoice;

use Magento\Catalog\Api\ProductRepositoryInterface;

/**
 * Product View block
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */

class View extends \Magento\Catalog\Block\Product\View
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magenest\PDFInvoice\Model\ResourceModel\Invoice\Collection
     */
    protected $_labelCollection;

    /**
     * View constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magenest\PDFInvoice\Model\ResourceModel\Invoice\Collection $labelCollection
     * @param \Magento\Framework\Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context     $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magenest\PDFInvoice\Model\ResourceModel\Invoice\Collection $labelCollection,
        \Magento\Framework\Registry $coreRegistry,

                array $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->_labelCollection     =   $labelCollection;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        $data = $this->_coreRegistry->registry('invoice');
        return $data;
    }
}
