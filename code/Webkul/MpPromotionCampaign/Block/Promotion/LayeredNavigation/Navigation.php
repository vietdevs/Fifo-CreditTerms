<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_MpPromotionCampaign
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\MpPromotionCampaign\Block\Promotion\LayeredNavigation;

class Navigation extends \Magento\LayeredNavigation\Block\Navigation
{
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Webkul\MpPromotionCampaign\Model\Layer\Resolver $layerResolver
     * @param \Magento\Catalog\Model\Layer\FilterList $filterList
     * @param \Magento\Catalog\Model\Layer\AvailabilityFlagInterface $visibilityFlag
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Webkul\MpPromotionCampaign\Model\Layer\Resolver $layerResolver,
        \Magento\Catalog\Model\Layer\FilterList $filterList,
        \Magento\Catalog\Model\Layer\AvailabilityFlagInterface $visibilityFlag,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $layerResolver,
            $filterList,
            $visibilityFlag
        );
    }
}
