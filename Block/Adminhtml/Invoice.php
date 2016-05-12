<?php
/**
 * Created by PhpStorm.
 */
namespace   Magenest\PDFInvoice\Block\Adminhtml;

class Invoice extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_blockGroup = 'Magenest_PDFInvoice';
        $this->_controller = 'adminhtml_invoice';
        $this->_addButtonLabel = __('Add New Rule');
        parent::_construct();
    }
}
