<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * @category  Magenest
 */

namespace Magenest\PDFInvoice\Block\Adminhtml\Invoice\Edit\Tab;

/**
 * Blog post edit form main tab
 */
class OderInvoice extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Magenest\Salesforce\Model\Status
     */
    protected $_status;

    /**
     * @var
     */
    protected $_fieldFactory;

    /**
     * DefaultInvoice constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param \Magenest\PDFInvoice\Model\Status $status
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magenest\PDFInvoice\Model\Status $status,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_status = $status;
        // $this->_fieldFactory = $fieldFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /* @var $model \Magenest\Salesforce\Model\Map */
        $model = $this->_coreRegistry->registry('invoice');

        $isElementDisabled = false;

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['enctype' => 'multipart/form-data']
        );
        $magentoFields = array();
        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __(' Variable in PDF Order file')]);

        $fieldset->addField(
            'pdf_order',
            'textarea',
            [
                'name' => 'pdf_order',
                'label' => __('Name to save PDF order '),
                'title' => __('Name to save PDF order '),
                'note' => 'To save an order with custom name including: customer’s name, date, etc.',
            ]
        );
        $fieldset->addField(
            'pdf_order_encode',
            'textarea',
            [
                'name' => 'pdf_order_encode',
                'label' => __('Information encoded in Barcode '),
                'title' => __('Information encoded in Barcode '),
                'note' => 'To choose information encoded in barcode on printed order, including customer’s name, date, etc. ',
            ]
        );





        $form->setValues($model->getData());
        $this->setForm($form);

        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Variable in PDF Order file');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Variable in PDF Order file');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

}
