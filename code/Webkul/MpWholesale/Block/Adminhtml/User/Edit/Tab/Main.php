<?php
/**
 * Webkul Software
 *
 * @category  Webkul
 * @package   Webkul_MpWholesale
 * @author    Webkul
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\MpWholesale\Block\Adminhtml\User\Edit\Tab;

class Main extends \Magento\Backend\Block\Widget\Form\Generic
{
    const CURRENT_WHOLESALE_USER_PASSWORD_FIELD = 'current_password';

    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    private $authSession;

    /**
     * @var \Magento\Framework\Locale\ListsInterface
     */
    private $localeLists;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Backend\Model\Auth\Session $authSession
     * @param \Magento\Framework\Locale\ListsInterface $localeLists
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\Locale\ListsInterface $localeLists,
        array $data = []
    ) {
        $this->authSession = $authSession;
        $this->localeLists = $localeLists;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form fields
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @return \Magento\Backend\Block\Widget\Form
     */
    public function _prepareForm()
    {
        /** @var $model \Magento\User\Model\User */
        $model = $this->_coreRegistry->registry('wholesale_user');
        $wholeSaleUsermodel = $this->_coreRegistry->registry('wholesale_userData');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('user_');

        $baseFieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Wholesale User Information')]
        );

        if ($model->getUserId()) {
            $baseFieldset->addField('user_id', 'hidden', ['name' => 'user_id']);
            $baseFieldset->addField(
                'username',
                'text',
                [
                    'name' => 'username',
                    'label' => __('User Name'),
                    'id' => 'username',
                    'title' => __('Wholesale User Name'),
                    'required' => true,
                    'readonly' =>true
                ]
            );
        } else {
            if (!$model->hasData('is_active')) {
                $model->setIsActive(1);
            }
            $baseFieldset->addField(
                'username',
                'text',
                [
                    'name' => 'username',
                    'label' => __('User Name'),
                    'id' => 'username',
                    'title' => __('Wholesale User Name'),
                    'required' => true
                ]
            );
        }

        if ($wholeSaleUsermodel->getEntityId()) {
            $baseFieldset->addField(
                'entity_id',
                'hidden',
                ['name' => 'entity_id']
            );
        }

        $baseFieldset->addField(
            'firstname',
            'text',
            [
                'name' => 'firstname',
                'label' => __('First Name'),
                'id' => 'firstname',
                'title' => __('Contact Person First Name'),
                'required' => true
            ]
        );

        $baseFieldset->addField(
            'lastname',
            'text',
            [
                'name' => 'lastname',
                'label' => __('Last Name'),
                'id' => 'lastname',
                'title' => __('Contact Person Last Name'),
                'required' => true
            ]
        );

        $baseFieldset->addField(
            'email',
            'text',
            [
                'name' => 'email',
                'label' => __('Email'),
                'id' => 'wholesaler_email',
                'title' => __('Wholesaler Email'),
                'class' => 'required-entry validate-email',
                'required' => true
            ]
        );
        $baseFieldset->addField(
            'title',
            'text',
            [
                'name' => 'title',
                'label' => __('Wholesaler Title'),
                'id' => 'wholeSaler_title',
                'title' => __('Wholesaler Title'),
                'class' => 'required-entry',
                'required' => true
            ]
        );

        $baseFieldset->addField(
            'description',
            'text',
            [
                'name' => 'description',
                'label' => __('Description'),
                'id' => 'wholeSaler_description',
                'title' => __('Wholesaler Description'),
                'class' => 'required-entry',
                'required' => true
            ]
        );
        $baseFieldset->addField(
            'address',
            'text',
            [
                'name' => 'address',
                'label' => __('Complete Address'),
                'id' => 'wholeSaler_address',
                'title' => __('wholesaler Address'),
                'class' => 'required-entry',
                'required' => true
            ]
        );
        $isNewObject = $model->isObjectNew();
        if ($isNewObject) {
            $passwordLabel = __('Password');
        } else {
            $passwordLabel = __('New Password');
        }
        $confirmationLabel = __('Password Confirmation');
        $this->_addPasswordFields(
            $baseFieldset,
            $passwordLabel,
            $confirmationLabel,
            $isNewObject
        );

        if ($this->authSession->getUser()->getId() != $model->getUserId()) {
            $baseFieldset->addField(
                'is_active',
                'select',
                [
                    'name' => 'is_active',
                    'label' => __('This account is'),
                    'id' => 'is_active',
                    'title' => __('Wholesaler Account Status'),
                    'class' => 'input-select',
                    'options' => ['1' => __('Active'), '0' => __('Inactive')]
                ]
            );
        }

        $baseFieldset->addField(
            'user_roles',
            'hidden',
            ['name' => 'user_roles', 'id' => '_user_roles']
        );

        $currentUserVerificationFieldset = $form->addFieldset(
            'current_user_verification_fieldset',
            ['legend' => __('Current Wholesale User Identity Verification')]
        );
        $currentUserVerificationFieldset->addField(
            self::CURRENT_WHOLESALE_USER_PASSWORD_FIELD,
            'password',
            [
                'name' => self::CURRENT_WHOLESALE_USER_PASSWORD_FIELD,
                'label' => __('Your Password'),
                'id' => self::CURRENT_WHOLESALE_USER_PASSWORD_FIELD,
                'title' => __('Your Password'),
                'class' => 'input-text validate-current-password'
                                . ' required-entry wholesale',
                'required' => true
            ]
        );

        $data= array_merge($model->getData(), $wholeSaleUsermodel->getData());
        unset($data['password']);
        unset($data[self::CURRENT_WHOLESALE_USER_PASSWORD_FIELD]);
        $form->setValues($data);

        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Add password input fields for wholesale
     *
     * @param \Magento\Framework\Data\Form\Element\Fieldset $fieldset
     * @param string $passwordLabel
     * @param string $confirmationLabel
     * @param bool $isRequired
     * @return void
     */
    public function _addPasswordFields(
        \Magento\Framework\Data\Form\Element\Fieldset $fieldset,
        $passwordLabel,
        $confirmationLabel,
        $isRequired = false
    ) {

        $requiredFieldClass = $isRequired ? ' required-entry' : '';
        $fieldset->addField(
            'password',
            'password',
            [
                'name' => 'password',
                'label' => $passwordLabel,
                'id' => 'customer_pass',
                'title' => $passwordLabel,
                'class' => 'wholesale input-text validate-admin-password'
                            . $requiredFieldClass,
                'required' => $isRequired
            ]
        );
        $fieldset->addField(
            'confirmation',
            'password',
            [
                'name' => 'password_confirmation',
                'label' => $confirmationLabel,
                'id' => 'confirmation',
                'title' => $confirmationLabel,
                'class' => 'wholesale input-text validate-cpassword'
                            . $requiredFieldClass,
                'required' => $isRequired
            ]
        );
    }
}
