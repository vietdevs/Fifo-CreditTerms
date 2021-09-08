<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_AbandonedCart
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\AbandonedCart\Controller\Adminhtml\Customer;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class CartDetails extends Action
{
    /**
     * enabled webkul abandoned cart
     **/
    const WK_ABANDONED_CART_ENABLED = "webkul_abandoned_cart/abandoned_cart_settings/enable_disable_abandoned_cart";

    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $_resultPageFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     **/
    protected $_scopeConfig;

    /**
     * @var \Magento\Framework\Controller\Result\Forward
     **/
    protected $_resultForward;

    /**
     * @param Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Controller\Result\Forward $resultForward
     * @param \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
     */
    public function __construct(
        Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Controller\Result\Forward $resultForward,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
    ) {
        parent::__construct($context);
        $this->_scopeConfig = $scopeConfig;
        $this->_resultForward = $resultForward;
        $this->_resultLayoutFactory = $resultLayoutFactory;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $moduleEnabled = $this->_scopeConfig->getValue(self::WK_ABANDONED_CART_ENABLED, $storeScope);
        if (!$moduleEnabled) {
            $resultForward = $this->_resultForward;
            $resultForward->forward('noroute');
            return $resultForward;
        }
        $resultLayout = $this->_resultLayoutFactory->create();
        return $resultLayout;
    }
}
