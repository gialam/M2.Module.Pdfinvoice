<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\PDFInvoice\Controller\Adminhtml\Invoice;

use \Magento\Backend\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\MediaStorage\Model\File\Uploader;
use Psr\Log\LoggerInterface;

/**
 * Class Save
 * @package Magenest\PDFInvoice\Controller\Adminhtml\Invoice
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * @var RequestInterface
     */
    protected $_request;

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * Save constructor.
     * @param Context $context
     * @param LoggerInterface $logger
     * @param RequestInterface $request
     * @param Filesystem $filesystem
     * @param UploaderFactory $fileUploaderFactory
     */
    public function __construct(
        Context $context,
        \Psr\Log\LoggerInterface $logger,
        RequestInterface $request,
        Filesystem $filesystem,
        UploaderFactory $fileUploaderFactory
    ) {
    
        $this->_request = $request;
        $this->_filesystem = $filesystem;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        parent::__construct($context);
        $this->_logger= $logger;
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {


        $data = $this->getRequest()->getPostValue();
        $this->_logger->debug(print_r($data, true));
        $this->_logger->debug(print_r($_FILES, true));
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!isset($data['logo'])) {
            $data['logo'] = [];
        }
        $image = $this->saveBackGround($_FILES['logo'], $data['logo']);
        if (is_array($data['logo']) && !empty($data['logo'])) {
            $data['logo'] =  $data['logo']['value'];
        }
        if ($image == 'deleted' || $image == '') {
            $data['logo'] = null;
        } else {
            $data['logo'] = $image;
        }

        if ($data) {
            $model = $this->_objectManager->create('Magenest\PDFInvoice\Model\Invoice');

            if (!empty($data['id'])) {
                $model->load($data['id']);
                if ($data['id'] != $model->getId()) {
                    throw new \Magento\Framework\Exception\LocalizedException(__('Wrong invoice rule.'));
                }
            }
//            unset($data['store_view']);
//            $this->_logger->debug(print_r($data, true));
            $model->addData($data);
            $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($model->getData());
            try {
                $model->save();
                $this->messageManager->addSuccess(__('Invoice has been saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());

            } catch (\Exception $e) {
                $this->messageManager->addError($e, __('Something went wrong while saving the invoice.'));
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
                return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            }
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param $value
     * @param $data
     * @return string
     */
    public function saveBackGround($value, $data)
    {
        if (!empty($value['name']) || !empty($data)) {
            /** Deleted file */
            if (!empty($data['delete']) && !empty($data['value'])) {
                $path = $this->_filesystem->getDirectoryRead(
                    DirectoryList::MEDIA
                );
                if ($path->isFile($data['value'])) {
                    $this->_filesystem->getDirectoryWrite(
                        DirectoryList::MEDIA
                    )->delete($data['value']);
                }
                if (empty($value['name'])) {
                    return 'deleted';
                }
            }
            if (empty($value['name']) && !empty($data)) {
                return $data['value'];
            }
            $path = $this->_filesystem->getDirectoryRead(
                DirectoryList::MEDIA
            )->getAbsolutePath(
                'pdfinvoice/template/'
            );
            try {
                /** @var $uploader \Magento\MediaStorage\Model\File\Uploader */
                $uploader = $this->_fileUploaderFactory->create(['fileId' => 'logo']);
                $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                $uploader->setAllowRenameFiles(false);
                $result = $uploader->save($path);
                if (is_array($result) && !empty($result['name'])) {
                    return 'pdfinvoice/template/'.$result['name'];
                }
            } catch (\Exception $e) {
                if ($e->getCode() != Uploader::TMP_NAME_EMPTY) {
                    $this->_logger->critical($e);
                }
                $this->_logger->critical($e);
            }
        }

        return '';
    }
    protected function _isAllowed()
    {
        return true;
    }
}
