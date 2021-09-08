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

namespace Webkul\MpWalletSystem\Controller\Adminhtml\Wallet;

use Webkul\MpWalletSystem\Controller\Adminhtml\Wallet as WalletController;
use Magento\Framework\Controller\ResultFactory;

/**
 * Webkul MpWalletSystem Controller
 */
class Addamount extends WalletController
{
    /**
     * Controller Execute function
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Webkul_MpWalletSystem::walletsystem');
        $resultPage->getConfig()->getTitle()->prepend(__('Adjust Amount To Wallet'));
        $resultPage->addBreadcrumb(__('Adjust Amount To Wallet'), __('Adjust Amount To Wallet'));
        $resultPage->addContent(
            $resultPage->getLayout()->createBlock(
                \Webkul\MpWalletSystem\Block\Adminhtml\Wallet\Edit::class
            )
        );
        $resultPage->addLeft(
            $resultPage->getLayout()->createBlock(
                \Webkul\MpWalletSystem\Block\Adminhtml\Wallet\Edit\Tabs::class
            )
        );
        return $resultPage;
    }
}
