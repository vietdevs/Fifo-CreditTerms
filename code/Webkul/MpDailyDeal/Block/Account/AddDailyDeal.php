<?php
 /**
  * Webkul_MpDailyDeal add Deal layout page.
  * @category  Webkul
  * @package   Webkul_MpDailyDeal
  * @author    Webkul
  * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
  * @license   https://store.webkul.com/license.html
  */
namespace Webkul\MpDailyDeal\Block\Account;

use Magento\Framework\View\Element\Template\Context;
use Magento\Customer\Model\Session;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Locale\CurrencyInterface;
use Webkul\Marketplace\Model\ProductFactory as MarketplaceProductFactory;

class AddDailyDeal extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var MarketplaceProductFactory
     */
    protected $marketplaceProductFactory;

    /**
     * @param Session                    $customerSession,
     * @param Context                    $context,
     * @param ProductRepositoryInterface $productRepository,
     * @param MarketplaceProductFactory  $marketplaceProductFactory,
     * @param array                      $data = []
     */
    public function __construct(
        Session $customerSession,
        Context $context,
        ProductRepositoryInterface $productRepository,
        CurrencyInterface $localeCurrency,
        MarketplaceProductFactory $marketplaceProductFactory,
        \Magento\Framework\Session\SessionManagerInterface $coreSession,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        $this->productRepository = $productRepository;
        $this->marketplaceProductFactory = $marketplaceProductFactory;
        $this->localeCurrency = $localeCurrency;
        $this->coreSession = $coreSession;
        parent::__construct($context, $data);
    }
    /**
     * getDealData
     * @return bool|array
     */
    public function getDealProduct()
    {
        $productId = $this->getRequest()->getParam('id');
        $dealData = $this->coreSession->getMpProductDealData();
        $this->coreSession->unsMpProductDealData();
        
        if ($dealData && is_object($dealData) && $dealData->getId()==$productId) {
            return $dealData;
        }
        $product = false;
        if ($productId) {
            $product = $this->marketplaceProductFactory->create()->getCollection()
                                ->addFieldToFilter('mageproduct_id', $productId)
                                ->addFieldToFilter('seller_id', $this->customerSession->getCustomerId())
                                ->getFirstItem();
            if ($product->getEntityId()) {
                return $this->productRepository->getById($productId);
            }
        }
        return $product;
    }

    /**
     * getDealSaveAction
     * @return string Deal Save Action Url
     */
    public function getDealSaveAction()
    {
        $productId = $this->getRequest()->getParam('id');
        $url = "";
        if ($productId) {
            $url = $this->getUrl(
                'mpdailydeal/account/savedeal',
                [
                    '_secure' => $this->getRequest()->isSecure(),
                    'id' => $productId
                ]
            );
        }
        return $url;
    }

    /**
     * getDateTimeAsLocale
     * @param string $data in base Time zone
     * @return string date in current Time zone
     */
    public function getDateTimeAsLocale($data)
    {
        if ($data) {
            return date_format(date_create($data), "m/d/Y H:i:s");
        }
        return $data;
    }

    /**
     * Retrieve currency Symbol.
     *
     * @return string
     */
    public function getCurrencySymbol()
    {
        return $this->localeCurrency->getCurrency($this->getBaseCurrencyCode())->getSymbol();
    }

    /**
     * @return integer
     */
    public function getUtcOffset($date)
    {
        return timezone_offset_get(new \DateTimeZone($this->_localeDate->getConfigTimezone()), new \DateTime($date));
    }
}
