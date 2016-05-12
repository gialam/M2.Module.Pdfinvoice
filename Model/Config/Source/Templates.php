<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * @category  Magenest
 */
namespace Magenest\PDFInvoice\Model\Config\Source;

class Templates implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var array
     */
    protected $_options;
    protected $_invoiceCollection;
    public function __construct(
        \Magenest\PDFInvoice\Model\Invoice $invoiceCollection,
        array $data = []
    ) {
    
        $this->_invoiceCollection = $invoiceCollection;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->_options) {
            $this->_options = [];
            foreach ($this->_invoiceCollection->getCollection() as $invoice) {
                $id = $invoice->getId();
                $name = $invoice->getName();
                if ($id != 0) {
                    $this->_options[] = ['value' => $id, 'label' => $name];
                }
            }
        }
        return $this->_options;

    }
}
