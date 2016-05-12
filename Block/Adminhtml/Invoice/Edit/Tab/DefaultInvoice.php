<?php
/**
 * Created by PhpStorm.<?php
*/

namespace Magenest\PDFInvoice\Block\Adminhtml\Invoice\Edit\Tab;

/**
 * Class DefaultInvoice
 * @package Magenest\PDFInvoice\Block\Adminhtml\Invoice\Edit\Tab
 */
class DefaultInvoice extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Magenest\PDFInvoice\Model\Status
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
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
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

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __(' Default Information')]);

        $fieldset->addField(
            'company_name',
            'text',
            [
                'name' => 'company_name',
                'label' => __('Company Name'),
                'title' => __('Company Name'),
            ]
        );
        $fieldset->addField(
            'address',
            'text',
            [
                'name' => 'address',
                'label' => __('Address'),
                'title' => __('Address'),
            ]
        );
        $fieldset->addField(
            'vat_number',
            'text',
            [
                'name' => 'vat_number',
                'label' => __('VAT Number'),
                'title' => __('VAT Number'),
            ]
        );
        $fieldset->addField(
            'vat_office',
            'text',
            [
                'name' => 'vat_office',
                'label' => __('VAT Office'),
                'title' => __('VAT Office'),
            ]
        );
        $fieldset->addField(
            'business_id',
            'text',
            [
                'name' => 'business_id',
                'label' => __('Business Id'),
                'title' => __('Business Id'),
            ]
        );


        $fieldset->addField(
            'logo',
            'image',
            [
                'name' => 'logo',
                'label' => __('Company Logo'),
                'title' => __('Company Logo'),
                'note' => 'Recommended image size: 160x40 pixels.jpeg, tiff, png files supported.. ',
            ]
        );
        $fieldset->addField(
            'email',
            'text',
            [
                'name' => 'email',
                'label' => __('Email'),
                'title' => __('Email'),
            ]
        );
        $fieldset->addField(
            'phone',
            'text',
            [
                'name' => 'phone',
                'label' => __('Phone'),
                'title' => __('Phone'),
            ]
        );
        $fieldset->addField(
            'fax',
            'text',
            [
                'name' => 'fax',
                'label' => __('Fax'),
                'title' => __('Fax'),
            ]
        );
        $fieldset->addField(
            'notes',
            'textarea',
            [
                'name' => 'notes',
                'label' => __('Notes'),
                'title' => __('Notes'),
            ]
        );
        $fieldset->addField(
            'terms_and_conditions',
            'text',
            [
                'name' => 'terms_and_conditions',
                'label' => __('Terms and Conditions'),
                'title' => __('Terms and Conditions'),
            ]
        );
        $fieldset->addField(
            'footer',
            'text',
            [
                'name' => 'footer',
                'label' => __('Footer'),
                'title' => __('Footer'),
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
        return __('Default Information');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Default Information');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * @param $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

}
