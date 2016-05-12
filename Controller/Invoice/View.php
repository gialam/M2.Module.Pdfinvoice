<?php
/**
 * Created by PhpStorm.
 * User: gialam
 * Date: 27/02/2016
 * Time: 10:50
 */
namespace Magenest\PDFInvoice\Controller\Invoice;

/**
 * post view
 */
class View extends \Magento\Framework\App\Action\Action
{
    /**
     *
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $post = $this->_objectManager->create('Magenest\PDFInvoice\Model\Invoice')->getCollection();

        $this->_objectManager->get('\Magento\Framework\Registry')->register('invoice', $post);

        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
    /* @return bool
     */
    protected function _isAllowed()
    {
        return true;
    }
}
