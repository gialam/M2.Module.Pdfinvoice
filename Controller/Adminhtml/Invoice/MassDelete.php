<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 *
 * Magenest_ProductLabel extension
 * NOTICE OF LICENSE
 *
 * @category  Magenest
 * @package   Magenest_ProductLabel
 * @author <ThaoPV>-thaopw@gmail.com
 */
namespace Magenest\PDFInvoice\Controller\Adminhtml\Invoice;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultFactory;
//use Magenest\ProductLabel\Model\PostFactory;
use Magenest\PDFInvoice\Model\ResourceModel\Invoice\CollectionFactory as InvoiceCollectionFactory;
use Magenest\PDFInvoice\Controller\Adminhtml\Invoice as InvoiceController;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Class MassDelete
 * @package Magenest\PDFInvoice\Controller\Adminhtml\Invoice
 */
class MassDelete extends InvoiceController
{
    /**
     * @var Filter
     */
    protected $_filter;

    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * MassDelete constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param InvoiceCollectionFactory $collectionFactory
     * @param Filter $filter
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        InvoiceCollectionFactory $collectionFactory,
        Filter $filter,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory

    ) {
        $this->_filter = $filter;
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context, $coreRegistry, $resultPageFactory, $collectionFactory);
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        $collections = $this->_filter->getCollection($this->_collectionFactory->create());
        $totals = 0;
        try {
            foreach ($collections as $item) {
            /** @var \Magenest\ProductLabel\Model\Post $item */
                $item->delete();
                $totals++;
            }

            $this->messageManager->addSuccess(__('A total of %1 record(s) have been deteled.', $totals));
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->_getSession()->addException($e, __('Something went wrong while delete the post(s).'));
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
