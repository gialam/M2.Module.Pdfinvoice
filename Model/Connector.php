<?php
/**
 * Created by PhpStorm.
 * User: gialam
 * Date: 01/03/2016
 * Time: 09:59
 */
namespace Magenest\PDFInvoice\Model;

/**
 * Class Connector
 * @package Magenest\PDFInvoice\Model
 */
class Connector
{
    const CONFIG_TEMPLATE = 'invoice_setting/pdfinvoice_label/pdfinvoice_set_template';
    const CONFIG_SEND_EMAIL= 'invoice_setting/pdfinvoice_label/pdfinvoice_send_email';
    const CONFIG_PRINT = 'invoice_setting/pdfinvoice_label/pdfinvoice_print';
    const CONFIG_PRINT_FRONTEND = 'invoice_setting/pdfinvoice_label/pdfinvoice_print_frontend';
    const CONFIG_COLOR_ORDER = 'invoice_setting/pdfinvoice_label/pdfinvoice_set_template_order';
    const CONFIG_COLOR_INVOICE = 'invoice_setting/pdfinvoice_label/pdfinvoice_set_template_invoice';
    const CONFIG_COLOR_CREDITMEMO = 'invoice_setting/pdfinvoice_label/pdfinvoice_set_template_creditmemo';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Connector constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(

        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array   $data = []
    ) {
    
        $this->_scopeConfig = $scopeConfig;

    }


    public function getTemplates()
    {
        $invoice_template =  $this->_scopeConfig->getValue(self::CONFIG_TEMPLATE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $invoice_template;
    }
    public function getEmail()
    {
        $invoice_send =  $this->_scopeConfig->getValue(self::CONFIG_SEND_EMAIL, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $invoice_send;
    }
    public function getPrint()
    {
        $invoice_print =  $this->_scopeConfig->getValue(self::CONFIG_PRINT, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $invoice_print;
    }
    public function getPrintFrontend()
    {
        $invoice_print_frontend =  $this->_scopeConfig->getValue(self::CONFIG_PRINT_FRONTEND, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $invoice_print_frontend;
    }
    public function getColorOder()
    {
        $invoice_color_order =  $this->_scopeConfig->getValue(self::CONFIG_COLOR_ORDER, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $invoice_color_order;
    }
    public function getColorInvoice()
    {
        $invoice_color_invoice =  $this->_scopeConfig->getValue(self::CONFIG_COLOR_INVOICE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $invoice_color_invoice;
    }
    public function getColorCreditmemo()
    {
        $invoice_color_creditmemor =  $this->_scopeConfig->getValue(self::CONFIG_COLOR_CREDITMEMO, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $invoice_color_creditmemor;
    }
}
