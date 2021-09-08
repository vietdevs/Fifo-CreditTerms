<?php
/**
 * Webkul Software
 *
 * @category  Webkul
 * @package   Webkul_MpPurchaseManagement
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\MpPurchaseManagement\Model\ResourceModel\Order;

use \Webkul\MpPurchaseManagement\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';
    /**
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Webkul\MpPurchaseManagement\Model\Order::class,
            \Webkul\MpPurchaseManagement\Model\ResourceModel\Order::class
        );
    }
    public function addStoreFilter($store, $withAdmin = true)
    {
        if (!$this->getFlag('store_filter_added')) {
            $this->performAddStoreFilter($store, $withAdmin);
        }
        return $this;
    }
}
