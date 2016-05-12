<?php
/**
 * Created by PhpStorm.
 */
namespace   Magenest\PDFInvoice\Model\ResourceModel;

class Invoice extends
 \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function _construct()
    {
        $this->_init('magenest_pdf_invoice', 'id');
    }
}
