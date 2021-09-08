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

namespace Webkul\MpWalletSystem\Model;

use \Webkul\MpWalletSystem\Model\ResourceModel\Wallettransaction\CollectionFactory as WalletTransactionCollection;

/**
 * Webkul MpWalletSystem Model Class
 */
class Cronjob
{
    /**
     * @var WebkulWalletsystemHelperData
     */
    protected $walletHelper;
    
    /**
     * @var MagentoCustomerModelCustomerFactory
     */
    protected $customerModel;
    
    /**
     * @var WebkulWalletsystemModelWallettransactionFactory
     */
    protected $walletTransaction;
    
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;
    
    /**
     * @var WalletTransactionCollection
     */
    private $walletTransactionCollection;
    
    /**
     * @var \Webkul\MpWalletSystem\Helper\Mail
     */
    protected $mailHelper;
    
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $timeZone;

    /**
     * Initialize dependencies
     *
     * @param WebkulWalletsystemHelperData                    $walletHelper
     * @param MagentoCustomerModelCustomerFactory             $customerFactory
     * @param WebkulWalletsystemModelWallettransactionFactory $walletTransactionFactory
     * @param MagentoFrameworkStdlibDateTimeDateTime          $date
     * @param WalletTransactionCollection                     $walletTransactionCollection
     * @param WebkulWalletsystemHelperMail                    $mailHelper
     * @param MagentoFrameworkStdlibDateTimeTimezoneInterface $timeZone
     */
    public function __construct(
        \Webkul\MpWalletSystem\Helper\Data $walletHelper,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Webkul\MpWalletSystem\Model\WallettransactionFactory $walletTransactionFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        WalletTransactionCollection $walletTransactionCollection,
        \Webkul\MpWalletSystem\Helper\Mail $mailHelper,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
    ) {
        $this->walletHelper = $walletHelper;
        $this->customerModel = $customerFactory;
        $this->walletTransaction = $walletTransactionFactory;
        $this->walletTransactionCollection = $walletTransactionCollection;
        $this->date = $date;
        $this->mailHelper = $mailHelper;
        $this->timeZone = $timezone;
    }

    /**
     * Controller's execute function
     *
     * @return redirect
     */
    public function execute()
    {
        $helper = $this->walletHelper;
        if ($helper->getcronEnable()) {
            $customerCollection = $this->customerModel
                ->create()->getCollection();
            if ($customerCollection->getSize()) {
                foreach ($customerCollection as $customer) {
                    $customerId = $customer->getEntityId();
                    $walletTotalAmount = $helper->getWalletTotalAmount($customerId);
                    $this->sendCustomerEmailForMonthlyStatement($customerId);
                }
            }
        }
        return $this;
    }

    /**
     * Send Customer Email For Monthly Statement function
     *
     * @param int $customerId
     */
    public function sendCustomerEmailForMonthlyStatement($customerId)
    {
        $helper = $this->walletHelper;
        $closingBalance = $helper->getWalletTotalAmount($customerId);
        $storeScopeTimeZone = $this->timeZone->getConfigTimezone();

        $firstDay = new \DateTime('first day of last month', new \DateTimeZone($storeScopeTimeZone));
        $firstDay = $firstDay->format('Y-m-d');

        $lastDay = new \DateTime('last day of last month', new \DateTimeZone($storeScopeTimeZone));
        $lastDay = new \DateTime($lastDay->format('Y-m-d').'+1 day', new \DateTimeZone($storeScopeTimeZone));

        $lastDay = $lastDay->format('Y-m-d');
        
        $lastMonth = new \DateTime('last month', new \DateTimeZone($storeScopeTimeZone));
        $month = $lastMonth->format('F');
        $year = $lastMonth->format('Y');

        $walletDataForCustomer = $this->walletTransactionCollection->create()
            ->getMonthlyTransactionDetails($customerId, $firstDay, $lastDay);
        if (array_key_exists(0, $walletDataForCustomer)) {
            $mailData = $walletDataForCustomer[0];
            $openingBalance = $closingBalance + $mailData['totaldebit'] - $mailData['totalcredit'];
            $mailData['month'] = $month;
            $mailData['year'] = $year;
            $mailData['openingbalance'] = $openingBalance;
            $mailData['closingbalance'] = $closingBalance;
            $mailData['customer_id'] = $customerId;
            $this->mailHelper->sendMonthlyTransaction($mailData);
        } else {
            $mailData['totaldebit'] = 0;
            $mailData['totalcredit'] = 0;
            $openingBalance = $closingBalance + $mailData['totaldebit'] - $mailData['totalcredit'];
            $mailData['month'] = $month;
            $mailData['year'] = $year;
            $mailData['openingbalance'] = $openingBalance;
            $mailData['closingbalance'] = $closingBalance;
            $mailData['customer_id'] = $customerId;
            $mailData['rechargewallet'] = 0;
            $mailData['cashbackamount'] = 0;
            $mailData['refundamount'] = 0;
            $mailData['admincredit'] = 0;
            $mailData['customercredits'] = 0;
            $mailData['usedwallet'] = 0;
            $mailData['refundwalletorder'] = 0;
            $mailData['admindebit'] = 0;
            $mailData['transfertocustomer'] = 0;
            $mailData['transfertobank'] = 0;
            $this->mailHelper->sendMonthlyTransaction($mailData);
        }
    }
}
