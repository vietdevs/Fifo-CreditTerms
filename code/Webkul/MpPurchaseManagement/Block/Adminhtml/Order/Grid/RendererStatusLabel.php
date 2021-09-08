<?php
/**
 *
 * @category  Webkul
 * @package   Webkul_MpPurchaseManagement
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\MpPurchaseManagement\Block\Adminhtml\Order\Grid;

use Webkul\MpPurchaseManagement\Model\Source\ShippingStatus;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;

class RendererStatusLabel extends AbstractRenderer
{
    /**
     * Array to store all options data
     *
     * @var array
     */
    protected $_actions = [];

    /**
     * @var ShippingStatus
     */
    protected $shippingStatus;

    /**
     * @param \Magento\Backend\Block\Context  $context
     * @param ShippingStatus                  $helper
     * @param array                           $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        ShippingStatus $shippingStatus,
        array $data = []
    ) {
        $this->shippingStatus = $shippingStatus;
        parent::__construct($context, $data);
    }

    /**
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $this->_actions = [];
        $actions = [$this->shippingStatus->getOptionArray()[$row->getShipStatus()]];
        $this->addToActions($actions);
        return $this->_actionsToHtml();
    }

    /**
     * Render options array as a HTML string
     *
     * @param array $actions
     * @return string
     */
    protected function _actionsToHtml(array $actions = [])
    {
        $html = [];
        $attributesObject = new \Magento\Framework\DataObject();

        if (empty($actions)) {
            $actions = $this->_actions;
        }
        foreach ($actions[0] as $action) {
            $html[] = '<span>' . $action . '</span>';
        }
        return implode('', $html);
    }

    /**
     * Add one action array to all options data storage
     *
     * @param array $actionArray
     * @return void
     */
    public function addToActions($actionArray)
    {
        $this->_actions[] = $actionArray;
    }
}
