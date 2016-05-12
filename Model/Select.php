<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 *
 * Magenest_Blog extension
 * NOTICE OF LICENSE
 *
 * @category  Magenest
 * @package   Magenest_Blog
 * @author ThaoPV
 *
 */
namespace Magenest\Blog\Model;

/**
 * Class Select
 * @package Magenest\Blog\Model
 */
class Select
{
    /**
     * @var \Magenest\PDFInvoice\Model\InvoiceFactory
     */
    protected $_collectionFactory;

    /**
     * Select constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magenest\PDFInvoice\Model\InvoiceFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magenest\PDFInvoice\Model\InvoiceFactory $collectionFactory,
        array $data = []
    ) {
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * @return array
     */
    public function toArrayOption()
    {
        $array = [];
        foreach ($this->_collectionFactory->create() as $item) {
            $array[] = ['label' => __($item['name']), 'value' => $item['id']];
        }
        return $array;
    }
}
