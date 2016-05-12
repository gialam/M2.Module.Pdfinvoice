<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 *
 * Magenest_Ticket extension
 * NOTICE OF LICENSE
 *
 * @category  Magenest
 * @package   Magenest_Ticket
 * @author ThaoPV <thaopw@gmail.com>
 */
namespace Magenest\PDFInvoice\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magenest\PDFInvoice\Model\Connector;
use Magento\Framework\Filesystem;
use Psr\Log\LoggerInterface;

/**
 * Class Pdf
 * @package Magenest\PDFInvoice\Helper
 */
class Pdf
{
    /**
     * @var Connector
     */
    protected $_connector;

    /**
     * @var Filesystem
     */
    protected $_filesystem;

    /**
     * @var \Magenest\PDFInvoice\Model\Invoice
     */
    protected $_pdfinvoiceCollection;
    /**
     * @param Connector $connector
     * @param Filesystem $fileSystem
     */
    public function __construct(
        Connector $connector,
        Filesystem $fileSystem,
        LoggerInterface $loggerInterface,
        \Magenest\PDFInvoice\Model\Invoice $pdfinvoiceCollection
    ) {
        $this->_logger = $loggerInterface;
        $this->_pdfinvoiceCollection = $pdfinvoiceCollection;
        $this->_filesystem = $fileSystem;
        $this->_connector  = $connector;
    }

    /**
     * @param \Zend_Pdf_Page $page
     * @param \Magento\Sales\Model\Order|\Magento\Sales\Model\Order\Invoice $model
     * @param string $type
     * @return mixed
     * @throws \Zend_Pdf_Exception
     */
    public function printPdf($page, $model, $type = 'order')
    {
        $templateId    = $this->_connector->getTemplates();
        $collection    = $this->_pdfinvoiceCollection->load($templateId);
        $fontRegular = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);
        $fontEdit = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA_BOLD);
        if ($type == 'order') {
            $color = $this->_connector->getColorOder();
            $code  = $model->getEntityId();
        } elseif ($type == 'invoice') {
            $color = $this->_connector->getColorInvoice();
            $code = $model->getId();
        }
        else {
            $color = $this->_connector->getColorCreditmemo();
            $code = $model->getId();
        }
//        $this->_logger->addDebug(print_r($color,true));
       if ($color) {
            if ($color == 1) {
                $imageBackground = \Zend_Pdf_Image::imageWithPath("app/code/Magenest/PDFInvoice/view/frontend/web/images/Yellow.jpg");
            } elseif ($color == 2) {
                $imageBackground = \Zend_Pdf_Image::imageWithPath("app/code/Magenest/PDFInvoice/view/frontend/web/images/Pink.jpg");
            } elseif ($color == 3) {
                $imageBackground = \Zend_Pdf_Image::imageWithPath("app/code/Magenest/PDFInvoice/view/frontend/web/images/SpringGreen.jpg");
            } else {
                $imageBackground = \Zend_Pdf_Image::imageWithPath("app/code/Magenest/PDFInvoice/view/frontend/web/images/DodgerBlue.jpg");
            }

            $page->drawImage($imageBackground, 0, 0, 595, 842);
        }

        if (($collection->getShowBarcode()) == 1) {
            $fileName = $this->getQrCode($code);
            $pathQrcode = $this->_filesystem->getDirectoryWrite(DirectoryList::MEDIA)->getAbsolutePath($fileName);
            $imageCode = \Zend_Pdf_Image::imageWithPath($pathQrcode);
            $page->drawImage(
                $imageCode,
                280,
                732,
                330,
                782
            );
        }
        $page = $this->printCompany($page);
        $billing = $model->getBillingAddress()->getData();
        $page = $this->printAddress($page, $billing);
        if ($model->getShippingAddressId()) {
            $shipping = $model->getShippingAddress()->getData();
            $page = $this->printAddress($page, $shipping, 'ship');
        }

        if ($type == 'order') {
            $payment       = $model->getPayment()->getAdditionalInformation();
            $paymentMethod = $payment['method_title'];
            $symbol = $model->getOrderCurrency()->getCurrencySymbol();
            $shippingMethod = $model->getShippingDescription();
            $items = $model->getAllItems();
            $status = $model->getStatus();
        } elseif ($type == 'invoice') {
            $payment       = $model->getOrder()->getPayment()->getAdditionalInformation();
            $paymentMethod = $payment['method_title'];
            $shippingMethod = $model->getOrder()->getShippingDescription();
            $symbol = $model->getOrder()->getOrderCurrency()->getCurrencySymbol();
            $items = $model->getOrder()->getAllItems();
            $status = $model->getOrder()->getStatus();
        }
        else
        {
            $payment       = $model->getOrder()->getPayment()->getAdditionalInformation();
            $paymentMethod = $payment['method_title'];
            $shippingMethod = $model->getOrder()->getShippingDescription();
            $symbol = $model->getOrder()->getBaseCurrency()->getCurrencySymbol();
            $items = $model->getAllItems();
            $status = $model->getOrder()->getStatus();

        }

        $page->drawText($paymentMethod, 50, 475, 'UTF-8');
        $page->drawText($shippingMethod, 318, 475, 'UTF-8');
        $page->setFont($fontRegular, 11);
        $page->setFillColor(\Zend_Pdf_Color_Html::color('#000000'));

        $page->setFont($fontRegular, 9);
        /** @var \Magento\Sales\Model\Order\Item|\Magento\Sales\Model\Order\Invoice\Item $item */

        $page->setFont($fontRegular, 9);
        $page = $this->printItems($page, $items, $symbol);
        $page->setFont($fontRegular, 11);
        $page = $this->printTotal($page, $model, $symbol);

        $noteHeight = 80;
        $page->setFont($fontRegular, 9);
        $noteInfo = wordwrap('Notes : '.$collection->getNotes(), 40, "\n");
        foreach (explode("\n", $noteInfo) as $text) {
            if ($collection->getNotes() !== '') {
                $page->drawText(strip_tags(ltrim($text)), 50, $noteHeight, 'UTF-8');
                $noteHeight -= 11;
            }
        }
        $termInfo = wordwrap('Terms : '.$collection->getTermsAndConditions(), 40, "\n");
        foreach (explode("\n", $termInfo) as $text) {
            if ($collection->getTermsAndConditions() !== '') {
                $page->drawText(strip_tags(ltrim($text)), 50, $noteHeight, 'UTF-8');
                $noteHeight -= 11;
            }
        }

        $page->setFont($fontRegular, 9);
        $page->drawText($collection->getFooter(), 260, 2, 'UTF-8');
        $page->setFont($fontRegular, 7);
        $page->drawText('1', 580, 2, 'UTF-8');
        $page->setFont($fontEdit, 12);
        if (($collection->getStatus()) == 1) {
            $page->drawText(__('Status : ') . $status, 50, 732, 'UTF-8');
        }

        return $page;
    }

    /**
     * Print Billing/Shipping Address
     *
     * @param \Zend_Pdf_Page $page
     * @param $data
     * @param string $type
     * @return mixed
     * @throws \Zend_Pdf_Exception
     */
    public function printAddress($page, $data, $type = 'bill')
    {
        $x = 318;
        $y = 645;
        if ($type == 'bill') {
            $x = 50;
        }
        $fontRegular = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);
        $page->setFont($fontRegular, 11);
        $page->drawText($data['firstname'].' '.$data['lastname'], $x, 660, 'UTF-8');

        $street = wordwrap($data['street'], 40, "\n");

        foreach (explode('\n', $street) as $text) {
            $page->drawText(ltrim($text), $x, $y, 'UTF-8');
            $y -= 15;
        }
        $address = wordwrap($data['city'].', '.$data['region'], 40, "\n");

        foreach (explode("\n", $address) as $text) {
            $page->drawText(ltrim($text), $x, $y, 'UTF-8');
            $y -= 15;
        }

        $page->drawText($data['telephone'], $x, $y, 'UTF-8');

        return $page;
    }

    /**
     * Print all items in an order/invoice to Pdf
     *
     * @param \Zend_Pdf_Page $page
     * @param array $items
     * @param string $symbol
     * @param string $type
     * @return mixed
     * @throws \Zend_Pdf_Exception
     */
    public function printItems($page, $items, $symbol, $type = 'order')
    {
        $line = 390;
        $i = 0;
        $fontRegular = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);
        $page->setFont($fontRegular, 9);
        /** @var \Magento\Sales\Model\Order\Item|\Magento\Sales\Model\Order\Invoice\Item $item */
        foreach ($items as $item) {
            $page->drawText($symbol.(float)$item->getPrice(), 342, $line, 'UTF-8');
            if ($type == 'order') {
                $qty = $item->getQtyOrdered();
            } else {
                $qty = $item->getQty();
            }
            $page->drawText(round((float)$qty, 2), 397, $line, 'UTF-8');
            $page->drawText($symbol.round($item->getBaseTaxAmount(), 2), 433, $line, 'UTF-8');
            $page->drawText($symbol.round($item->getRowTotalInclTax(), 2), 475, $line, 'UTF-8');

            $name = $item->getName();
            $name = wordwrap($name, 50, "\n");
            foreach (explode("\n", $name) as $text) {
                $page->drawText(strip_tags(ltrim($text)), 50, $line, 'UTF-8');
                $line -= 15;
            }
            $page->setFont($fontRegular, 7);
            $page->drawText(__('SKU : ').$item->getSku(), 50, $line, 'UTF-8');
            $page->setFont($fontRegular, 9);
            $page->drawLine(42, $line, 555, $line);
            $line -= 15;
            $i++;
            if ($i > 9) {
                break;
            }
        }


        return $page;
    }

    /**
     * Print Total of an Invoice/Order
     *
     * @param \Zend_Pdf_Page $page
     * @param \Magento\Sales\Model\Order|\Magento\Sales\Model\Order\Invoice $model
     * @param string $symbol
     * @return mixed
     * @throws \Zend_Pdf_Exception
     */
    public function printTotal($page, $model, $symbol)
    {
        $height = 80;
        $page->drawText('DISCOUNT ', 426, $height + 15, 'UTF-8');
        $page->drawText($symbol.$model->getDiscountAmount(), 492, $height + 15, 'UTF-8');
        $page->drawText($symbol.$model->getSubtotal(), 492, $height, 'UTF-8');
        $page->drawText($symbol.$model->getBaseShippingAmount(), 492, $height - 17, 'UTF-8');
        $page->drawText($symbol.$model->getTaxAmount(), 492, $height - 36, 'UTF-8');
        $fontEdit = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA_BOLD);
        $page->setFont($fontEdit, 12);
        $page->drawText($symbol.' '.$model->getGrandTotal(), 492, $height - 54, 'UTF-8');

        return $page;
    }

    /**
     * @param \Zend_Pdf_Page $page
     * @param \Magenest\PDFInvoice\Model\Invoice $collection
     * @return mixed
     * @throws \Zend_Pdf_Exception
     */
    public function printCompany($page)
    {
        $templateId    = $this->_connector->getTemplates();
        $collection    = $this->_pdfinvoiceCollection->load($templateId);
        $fontRegular = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);
        $fontEdit       = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA_BOLD);
        $page->setFont($fontRegular, 11);
        $logo = $collection->getLogo();
        $logoCompany = $this->_filesystem->getDirectoryWrite(DirectoryList::MEDIA)->getAbsolutePath($logo);

        if (is_file($logoCompany)) {
            $image = \Zend_Pdf_Image::imageWithPath($logoCompany);
            $page->drawImage($image, 450, 800, 500, 830);
        } else {
            $page->drawText('', 330, 560, 'UTF-8');
        }
        $positionCompany = 769;
        $page->setFillColor(\Zend_Pdf_Color_Html::color('#FF0000'))
            ->setFont($fontEdit, 16)
            ->drawText($collection->getCompanyName(), 430, 780, 'UTF-8');
        $page->setFillColor(\Zend_Pdf_Color_Html::color('#000000'));
        $page->setFont($fontRegular, 9);
        $address_company_w = wordwrap('Address :'.$collection->getAddress(), 40, "\n");
        foreach (explode("\n", $address_company_w) as $text) {
            if ($collection->getAddress() !== '') {
                $page->drawText(strip_tags(ltrim($text)), 370, $positionCompany, 'UTF-8');
                $positionCompany -= 11;
            }
        }
        $page->drawText('Phone : ' . $collection->getPhone(), 370, $positionCompany-11, 'UTF-8');
        $page->drawText('Fax : ' . $collection->getFax(), 370, $positionCompany-22, 'UTF-8');
        $page->drawText('Business ID : ' . $collection->getBusinessId(), 370, $positionCompany-33, 'UTF-8');
        $page->drawText('Email : ' . $collection->getEmail(), 370, $positionCompany-44, 'UTF-8');
        return $page;
    }

    /**
     * @param $code
     * @return string
     */
    public function getQrCode($code)
    {
        $url = "http://api.qrserver.com/v1/create-qr-code/?&size=120x120&data=" . $code;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $raw = curl_exec($ch);
        curl_close($ch);
        $saveto = ("pub/media/qr_".$code.".png");
        if (file_exists($saveto)) {
            unlink($saveto);
        }
        $fp = fopen($saveto, 'x');
        fwrite($fp, $raw);
        fclose($fp);
        $file = 'qr_'.$code.".png";

        return  $file;
    }

    /**
     * @param \Magento\Sales\Model\Order|\Magento\Sales\Model\Order\Invoice $model
     * @param string $type
     * @return \Zend_Pdf_Page
     * @throws \Zend_Pdf_Exception
     */
    public function newPdfPage($model,$type='order')
    {
        $page  = new \Zend_Pdf_Page('595:842');
        $fontRegular = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);
        $fontEdit = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA_BOLD);
        $page->setFont($fontRegular, 11);
        $Preview   = 11;
        $PageTotal = 33;
        $printTotal= 30;
        $i = 0;
        $line = 750;
        $templateId    = $this->_connector->getTemplates();
        $collection    = $this->_pdfinvoiceCollection->load($templateId);
        if ($type == 'order') {
            $items = $model->getAllItems();
            $symbol = $model->getBaseCurrency()->getCurrencySymbol();
        } elseif ($type == 'invoice') {
            $items =  $model->getOrder()->getAllItems();
            $symbol = $model->getOrder()->getOrderCurrency()->getCurrencySymbol();
        }
        else
        {
            $items = $model->getAllItems();
            $symbol = $model->getOrder()->getBaseCurrency()->getCurrencySymbol();
        }
        /** @var \Magento\Sales\Model\Order\Item|\Magento\Sales\Model\Order\Invoice\Item $item */
        foreach ($items as $item) {
            $i++;
            if ($i < $Preview) {
                continue;
            }
            $page->setFont($fontRegular, 9);
            $name = $item->getName();
            $page->drawText(substr($name, 0, 50), 50, $line, 'UTF-8');
            $page->drawText($symbol.' '.$item->getPrice(), 342, $line, 'UTF-8');

            if ($type == 'order') {
                $qty = $item->getQtyOrdered();
            } else {
                $qty = $item->getQty();
            }
            $page->drawText(round((float)$qty), 397, $line, 'UTF-8');
            $page->drawText($symbol.' '.$model->getBaseTaxAmount(), 433, $line, 'UTF-8');

            if (strlen($name) > 50) {
                $page->drawText(substr($name, 50, 100), 50, $line, 'UTF-8');
                $line -= 15;
                if (strlen($name) > 100) {
                    $page->drawText(substr($name, 100), 50, $line, 'UTF-8');
                    $line -= 15;
                }
            }

            $sub  = ($item->getPrice()) * ($qty) ;
            $page->drawText($symbol.' '.$sub, 475, $line, 'UTF-8');

            $line -= 15;
            $page->setFont($fontRegular, 7);
            $page->drawText(__('SKU :').$item->getSku(), 50, $line, 'UTF-8');
            $line -= 5;
            $page->drawLine(42, $line, 555, $line);
            $line -= 8;
            if ($i > $PageTotal) {
                break;
            }
        }
        $page->setFont($fontRegular, 9);
        $positionCost = $line - 20;
        if ($i < $printTotal) {
            $page->drawText('DISCOUNT ', 426, $positionCost + 15, 'UTF-8');
            $page->drawText($symbol.' '.$model->getDiscountAmount(), 492, $positionCost + 15, 'UTF-8');
            $page->drawText('SUBTOTAL ', 426, $positionCost, 'UTF-8');
            $page->drawText($symbol.' '.$model->getSubtotal(), 492, $positionCost, 'UTF-8');
            $page->drawText('SHIPPING & HANDLING ', 374, $positionCost - 17, 'UTF-8');
            $page->drawText($symbol.' '.$model->getBaseShippingAmount(), 492, $positionCost - 17, 'UTF-8');
            $page->drawText('TAX ', 460, $positionCost - 36, 'UTF-8');
            $page->drawText($symbol.' '.$model->getTaxAmount(), 492, $positionCost - 36, 'UTF-8');
            $page->setFont($fontEdit, 12);
            $page->drawText('GRAND TOTAL ', 400, $positionCost - 54, 'UTF-8');
            $page->drawText($symbol.' '.$model->getGrandTotal(), 492, $positionCost - 54, 'UTF-8');

        }
        $page->setFont($fontRegular, 11);
        $page->drawText($collection->getFooter(), 260, 2, 'UTF-8');
        $checkPage = ceil($Preview/20 + 1);
        $page->setFont($fontRegular, 9);
        $page->drawText($checkPage, 580, 2, 'UTF-8');

        return $page;

    }
}
