<?php
/**
 * @category  Webkul
 * @package   Webkul_MpPurchaseManagement
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\MpPurchaseManagement\Controller\Order;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\RequestInterface;

class Cancel extends Action
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var Magento\Customer\Model\Url
     */
    protected $customerUrl;

    /**
     * @var \Webkul\MpPurchaseManagement\Model\OrderItemFactory
     */
    protected $orderItemFactory;

    /**
     * @var \Webkul\MpPurchaseManagement\Model\OrderFactory
     */
    protected $orderFactory;

    /**
     * @var \Webkul\Marketplace\Helper\Data
     */
    protected $mpHelper;

    /**
     * @var \Webkul\MpPurchaseManagement\Helper\Data
     */
    protected $helper;

    /**
     * @var \Webkul\MpPurchaseManagement\Model\ResourceModel\OrderItem\Collection
     */
    protected $orderItemCollection;

    /**
     * @param Context                                             $context
     * @param \Magento\Customer\Model\Session                     $customerSession
     * @param \Magento\Customer\Model\Url                         $customerUrl
     * @param \Webkul\MpPurchaseManagement\Model\OrderItemFactory $orderItemFactory
     * @param \Webkul\MpPurchaseManagement\Model\OrderFactory     $orderFactory
     * @param \Webkul\Marketplace\Helper\Data                     $mpHelper
     * @param \Webkul\MpPurchaseManagement\Helper\Data            $helper
     */
    public function __construct(
        Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\Url $customerUrl,
        \Webkul\MpPurchaseManagement\Model\OrderItemFactory $orderItemFactory,
        \Webkul\MpPurchaseManagement\Model\OrderFactory $orderFactory,
        \Webkul\Marketplace\Helper\Data $mpHelper,
        \Webkul\MpPurchaseManagement\Helper\Data $helper
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->customerUrl = $customerUrl;
        $this->orderItemFactory = $orderItemFactory;
        $this->orderFactory = $orderFactory;
        $this->mpHelper = $mpHelper;
        $this->helper = $helper;
    }

    /**
     * Check customer authentication
     *
     * @param RequestInterface $request
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function dispatch(RequestInterface $request)
    {
        $loginUrl = $this->customerUrl->getLoginUrl();
        if (!$this->customerSession->authenticate($loginUrl)) {
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
        }
        return parent::dispatch($request);
    }

    /**
     * cancel purchase order
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!$this->helper->getModuleStatus()) {
            $this->messageManager->addError(__('Module is disabled by admin, Please contact to admin!"'));
            return $resultRedirect->setPath('customer/account', ['_secure'=>$this->getRequest()->isSecure()]);
        }
        $orderId = $this->getRequest()->getParam('id');
        if (!$orderId) {
            $this->messageManager->addError(__('Some error occured, Please try again'));
            return $resultRedirect->setPath('mppurchasemanagement/order/list');
        }
        $order = $this->orderFactory->create()->load($orderId);
        if ($this->checkConditions($order)) {
            $order->setStatus(\Webkul\MpPurchaseManagement\Model\Order::STATUS_CANCELLED);
            $order->setEntityId($orderId);
            $order->save();

            //cancel order items shipment
            foreach ($this->getItemCollection() as $item) {
                $status = \Webkul\MpPurchaseManagement\Model\OrderItem::STATUS_CANCELLED;
                $this->setStatus($item, $status);
            }
            $this->messageManager->addSuccess(__('Purchase Order successfully cancelled'));
        }
        return $resultRedirect->setPath('mppurchasemanagement/order/view', ['id' => $orderId]);
    }

    /**
     * get order item collection
     * @return \Webkul\MpPurchaseManagement\Model\ResourceModel\OrderItem\Collection
     */
    protected function getItemCollection()
    {
        if (!$this->orderItemCollection) {
            $this->orderItemCollection = $this->orderItemFactory->create()->getCollection()
                           ->addFieldToFilter('purchase_order_id', ['eq'=>$this->getRequest()->getParam('id')])
                           ->addFieldToFilter('seller_id', ['eq'=>$this->mpHelper->getCustomerId()]);
        }
        return $this->orderItemCollection;
    }

    /**
     * check the conditions for cancelling purchase order
     * @param  \Webkul\MpPurchaseManagement\Model\Order
     * @return bool
     */
    protected function checkConditions($order)
    {
        //check order ownership
        $collection = $this->getItemCollection();
        if ($collection->getSize()==0) {
            return false;
        }

        //check whether order exist or not
        if (!$order->getEntityId()) {
            return false;
        }

        //check order status
        if ($order->getStatus()!=\Webkul\MpPurchaseManagement\Model\Order::STATUS_NEW) {
            $this->messageManager->addError(__('This order cannot be cancelled'));
            return false;
        }
        return true;
    }

    /**
     * Update Ship Status
     *
     * @param collection $model
     * @param int $status
     * @return void
     */
    public function setStatus($model, $status)
    {
        $model->setShipStatus($status);
        $model->setEntityId($model->getEntityId());
        $model->save();
    }
}
