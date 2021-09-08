<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_MpVendorAttributeManager
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\MpVendorAttributeManager\Controller\Adminhtml\Group;

class Attributes extends \Magento\Backend\App\Action
{
    protected $resultLayoutFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
    ) {
        $this->resultLayoutFactory = $resultLayoutFactory;
        $this->_registry = $registry;
        parent::__construct($context);
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_MpVendorAttributeManager::group');
    }

    public function execute()
    {
        $this->_registry->register('group_id', $this->getRequest()->getParam('id'));
        $resultLayout = $this->resultLayoutFactory->create();
        $resultLayout->getLayout()
                    ->getBlock('group.edit.tab.attributes');
        return $resultLayout;
    }
}
