<?php
/**
 * Webkul_MpAuction Detail block.
 * @category  Webkul
 * @package   Webkul_MpAuction
 * @author    Webkul
 * @copyright Copyright (c)  Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\MpAuction\Block\Account;

use Webkul\MpAuction\Model\ResourceModel\Amount\CollectionFactory;
use Webkul\MpAuction\Helper\Data as AuctionHelperData;

/**
 * Auction detail block
 */
class BidsRecord extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    protected $_template = 'mpauction/detail.phtml';

    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    private $priceHelper;

    /**
     * @var \Webkul\MpAuction\Model\ResourceModel\Amount\CollectionFactory
     */
    private $auctionAmtCollection;

    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    private $product;

    protected $auctionDetails;

    /**
     * @var Webkul\MpAuction\Helper\Data
     */
    private $auctionHelperData;

    /**
     * @param Magento\Framework\View\Element\Template\Context $context
     * @param Magento\Customer\Model\Session                  $customerSession
     * @param CollectionFactory                               $aucAmtCollFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Model\ProductFactory $product,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        CollectionFactory $aucAmtCollFactory,
        AuctionHelperData $auctionHelperData,
        array $data = []
    ) {
        $this->_priceHelper = $priceHelper;
        $this->auctionAmtCollection = $aucAmtCollFactory;
        $this->customerSession = $customerSession;
        $this->product = $product;
        $this->auctionHelperData = $auctionHelperData;
        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('Auction Details'));
    }

    /**
     * @return bool|\Magento\Sales\Model\ResourceModel\Order\Collection
     */
    public function getAuctionDetails()
    {
        if (!($customerId = $this->auctionHelperData->getCurrentCustomerId())) {
            return false;
        }
        if (!$this->auctionDetails) {
            $this->auctionDetails = $this->auctionAmtCollection->create()->addFieldToSelect('*')
                                            ->addFieldToFilter('customer_id', $customerId)
                                            ->setOrder('entity_id', 'desc');
        }
        return $this->auctionDetails;
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getAuctionDetails()) {
            $pager = $this->getLayout()
            ->createBlock(\Magento\Theme\Block\Html\Pager::class, 'sales.order.history.pager')
            ->setCollection($this->getAuctionDetails());
            $this->setChild('pager', $pager);
            $this->getAuctionDetails()->load();
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @param int $id
     * @return string
     */
    public function getDeleteUrl($id)
    {
        return $this->getUrl('mpauction/account/deletebid', ['id' => $id]);
    }

    /**
     * @param int $productId
     * @return string
     */
    public function getProductDetail($productId)
    {
        $pro = $this->product->create()->load($productId);
        return ['name'=> $pro->getName(), 'url' => $pro->getProductUrl()];
    }

    /**
     * get Formated price
     * @param $amount float
     * @return string
     */
    public function formatPrice($amount)
    {
        return $this->_priceHelper->currency($amount, true, false);
    }

    /**
     * get Winning Status Label
     * @param $winningStatus int
     * @return string
     */
    public function winningStatus($auctionData)
    {
        if ($auctionData->getStatus() == 0) {
            $status = $auctionData->getWinningStatus() == 1 ?
                                                        __("Winner") : __("Lost");
        } else {
            $status = __("Pending");
        }
        return $status;
    }

    /**
     * get Auction Status Label
     * @param $status int
     * @return string
     */
    public function status($status)
    {
        $label = [0 =>__('Complete'), 1 =>__('Pending')];
        return isset($label[$status]) ? $label[$status] : '--';
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('customer/account/');
    }
}
