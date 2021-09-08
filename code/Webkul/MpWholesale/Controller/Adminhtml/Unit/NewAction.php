<?php
/**
 * Webkul Software
 *
 * @category  Webkul
 * @package   Webkul_MpWholesale
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\MpWholesale\Controller\Adminhtml\Unit;

class NewAction extends \Webkul\MpWholesale\Controller\Adminhtml\Unit
{
    /**
     * @return void
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}
