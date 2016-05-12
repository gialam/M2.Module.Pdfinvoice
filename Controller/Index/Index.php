<?php
/**
 * Created by PhpStorm.
 */
namespace   Magenest\PDFInvoice\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     *  Index   action
     *
     *  @return     $this
     */
    protected $resultPageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context   $context,
        \Magento\Framework\View\Result\PageFactory  $resultPageFactory
    ) {
        $this->resultPageFactory    =   $resultPageFactory;
        parent::__construct($context);
    }
    public function execute()
    {
        $resultPage     =   $this->resultPageFactory->create();
        return  $resultPage;

    }
}
