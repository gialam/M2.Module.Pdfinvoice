<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\PDFInvoice\Model\ResourceModel\Invoice;

/**
 *  Invoice Collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';
    /**
     *  Initialize  resource    collection
     *
     *  @return     void
     */
    public function _construct()
    {
        $this->_init('Magenest\PDFInvoice\Model\Invoice', 'Magenest\PDFInvoice\Model\ResourceModel\Invoice');
    }
}
