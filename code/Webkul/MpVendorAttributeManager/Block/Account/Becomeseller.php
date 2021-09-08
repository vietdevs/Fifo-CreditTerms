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
namespace Webkul\MpVendorAttributeManager\Block\Account;

class Becomeseller extends \Webkul\Marketplace\Block\Account\Becomeseller
{
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Webkul\MpVendorAttributeManager\Helper\Data $helper
     * @param \Webkul\Marketplace\Helper\Data $mpHelper
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Webkul\MpVendorAttributeManager\Helper\Data $helper,
        \Webkul\Marketplace\Helper\Data $mpHelper,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        array $data = []
    ) {
        $this->helper = $helper;
        $this->mpHelper = $mpHelper;
        $this->jsonHelper = $jsonHelper;
        parent::__construct($context, $data);
    }

    public function getMpHelper()
    {
        return $this->mpHelper;
    }

    public function getHelper()
    {
        return $this->helper;
    }

    public function getJsonHelper()
    {
        return $this->jsonHelper;
    }
}
