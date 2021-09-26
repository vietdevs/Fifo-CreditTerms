<?php
/**
 * @category   Fifo
 * @package    Fifo_CreditTerms
 * @author     abdulmalik422@gmail.com
 * @copyright  This file was generated by using Module Creator(http://code.vky.co.in/magento-2-module-creator/) provided by VKY <viky.031290@gmail.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Fifo\CreditTerms\Block\Adminhtml\Applications\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class Main extends Generic implements TabInterface
{
    protected $_wysiwygConfig;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        array $data = []
    )
    {
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Credit Term Applications Information');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Credit Term Applications Information');
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

    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('current_fifo_creditterms_applications');
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('applications_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Credit Term Applications Information')]);
        if ($model->getId()) {
            $fieldset->addField('creditterms_application_id', 'hidden', ['name' => 'creditterms_application_id']);
        }

        $fieldset->addField(
            'application_status',
            'select',
            [
                'name' => 'application_status',
                'label' => __('Application Status'),
                'title' => __('Application Status'),
                'options'   => [0 => 'New', 1 => 'Approved', 2 => 'Rejected'],
                'required' => true
            ]
        );

        $fieldset->addField(
            'email',
            'text',
            [
                'name' => 'email',
                'label' => __('Email'),
                'title' => __('Email'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'cr_number',
            'text',
            [
                'name' => 'cr_number',
                'label' => __('Commercial Registration Number'),
                'title' => __('Commercial Registration Number'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'company_name',
            'text',
            [
                'name' => 'company_name',
                'label' => __('Company Name'),
                'title' => __('Company Name'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'restaurant_name',
            'text',
            [
                'name' => 'restaurant_name',
                'label' => __('Restaurant name'),
                'title' => __('Restaurant name'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'number_of_branches',
            'text',
            [
                'name' => 'number_of_branches',
                'label' => __('Number of branches'),
                'title' => __('Number of branches'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'location',
            'text',
            [
                'name' => 'location',
                'label' => __('Location'),
                'title' => __('Location'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'region',
            'text',
            [
                'name' => 'region',
                'label' => __('Region'),
                'title' => __('Region'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'owner_name',
            'text',
            [
                'name' => 'owner_name',
                'label' => __('Owner name'),
                'title' => __('Owner name'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'contact_person_name',
            'text',
            [
                'name' => 'contact_person_name',
                'label' => __('Contact Person Name'),
                'title' => __('Contact Person Name'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'contact_number',
            'text',
            [
                'name' => 'contact_number',
                'label' => __('Contact Number'),
                'title' => __('Contact Number'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'contact_email',
            'text',
            [
                'name' => 'contact_email',
                'label' => __('Contact Email'),
                'title' => __('Contact Email'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'buyer_credit_terms',
            'text',
            [
                'name' => 'buyer_credit_terms',
                'label' => __('Buyer Credit Terms'),
                'title' => __('Buyer Credit Terms'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'paylater_checkout',
            'select',
            [
                'name' => 'paylater_checkout',
                'label' => __('Paylater Checkout'),
                'title' => __('Paylater Checkout'),
                'options'   => [0 => 'Disable', 1 => 'Enable'],
                'required' => true
            ]
        );

        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
