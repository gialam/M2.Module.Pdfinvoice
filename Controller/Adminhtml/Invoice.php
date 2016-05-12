<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\PDFInvoice\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magenest\PDFInvoice\Model\ResourceModel\Invoice\CollectionFactory as PostCollectionFactory;

/**
 * Reviews admin controller
 */
abstract class Invoice extends Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * @var PostCollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;


    /**
     * Invoice constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param PostCollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        PostCollectionFactory $collectionFactory
    ) {
        $this->_context = $context;
        $this->coreRegistry = $coreRegistry;
//        $this->_postFactory = $postFactory;
        $this->_collectionFactory = $collectionFactory;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magenest_PDFInvoice::invoice')
            ->addBreadcrumb(__('Manage PDF Invoice'), __('Manage PDF Invoice'));
        $resultPage->getConfig()->getTitle()->set(__('Manage PDF Invoice'));

        return $resultPage;
    }
    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return true;

    }
}
