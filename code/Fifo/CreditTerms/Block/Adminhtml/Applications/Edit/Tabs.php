<?php
/**
 * @category   Fifo
 * @package    Fifo_CreditTerms
 * @author     abdulmalik422@gmail.com
 * @copyright  This file was generated by using Module Creator(http://code.vky.co.in/magento-2-module-creator/) provided by VKY <viky.031290@gmail.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Fifo\CreditTerms\Block\Adminhtml\Applications\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('fifo_creditterms_applications_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Applications'));
    }
}
