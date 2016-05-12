<?php
/**
 * Created by PhpStorm.
 */
//Create	a	model	that	interacts	with	the	database	table
namespace Magenest\PDFInvoice\Model;

/**
 * Class Invoice
 * @package Magenest\PDFInvoice\Model
 * @method boolean getShowBarcode()
 * @method Invoice setDemo($demo)
 */
class Invoice extends \Magento\Framework\Model\AbstractModel
{

    public function _construct()
    {
        $this->_init('Magenest\PDFInvoice\Model\ResourceModel\Invoice');
    }
}
