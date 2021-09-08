<?php
/**
 * Webkul Software
 *
 * @category  Webkul
 * @package   Webkul_MpWalletSystem
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\MpWalletSystem\Rewrite\CustomerData;

class Cart extends \Magento\Checkout\CustomerData\Cart
{
    /**
     * @var \Webkul\MpWalletSystem\Helper\Data
     */
    private $walletHelper;

    /**
     * Initialize dependencies
     *
     * @param \Magento\Checkout\Model\Session                  $checkoutSession
     * @param \Magento\Catalog\Model\ResourceModel\Url         $catalogUrl
     * @param \Magento\Checkout\Model\Cart                     $checkoutCart
     * @param \Magento\Checkout\Helper\Data                    $checkoutHelper
     * @param \Magento\Checkout\CustomerData\ItemPoolInterface $itemPoolInterface
     * @param \Magento\Framework\View\LayoutInterface          $layout
     * @param \Webkul\MpWalletSystem\Helper\Data               $walletHelper
     * @param array                                            $data
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Catalog\Model\ResourceModel\Url $catalogUrl,
        \Magento\Checkout\Model\Cart $checkoutCart,
        \Magento\Checkout\Helper\Data $checkoutHelper,
        \Magento\Checkout\CustomerData\ItemPoolInterface $itemPoolInterface,
        \Magento\Framework\View\LayoutInterface $layout,
        \Webkul\MpWalletSystem\Helper\Data $walletHelper,
        array $data = []
    ) {
        parent::__construct(
            $checkoutSession,
            $catalogUrl,
            $checkoutCart,
            $checkoutHelper,
            $itemPoolInterface,
            $layout,
            $data
        );
        $this->walletHelper = $walletHelper;
    }

    /**
     * Get array of last added items
     *
     * @return \Magento\Quote\Model\Quote\Item[]
     */
    protected function getRecentItems()
    {
        $walletProductId = $this->walletHelper->getWalletProductId();
        $items = [];
        if (!$this->getSummaryCount()) {
            return $items;
        }

        foreach (array_reverse($this->getAllQuoteItems()) as $item) {
            if (!$item->getProduct()->isVisibleInSiteVisibility()) {
                $product =  $item->getOptionByCode('product_type') !== null
                    ? $item->getOptionByCode('product_type')->getProduct()
                    : $item->getProduct();

                $products = $this->catalogUrl->getRewriteByProductStore([$product->getId() => $item->getStoreId()]);
                if (!isset($products[$product->getId()])) {
                    if ($product->getId() == $walletProductId) {
                        $items[] = $this->itemPoolInterface->getItemData($item);
                    }
                    continue;
                }
                $urlDataObject = new \Magento\Framework\DataObject($products[$product->getId()]);
                $item->getProduct()->setUrlDataObject($urlDataObject);
            }
            $items[] = $this->itemPoolInterface->getItemData($item);
        }
        return $items;
    }
}
