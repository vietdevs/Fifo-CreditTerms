<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_MpPurchaseManagement
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\MpPurchaseManagement\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Webkul\Marketplace\Model\ControllersRepository;

class MarketplaceControllers implements DataPatchInterface
{
    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var \Webkul\Marketplace\Model\ControllersRepository
     */
    private $controllersRepository;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param ControllersRepository $controllersRepository
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        ControllersRepository $controllersRepository
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->controllersRepository = $controllersRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();
        $data = [];
        if (!($this->controllersRepository->getByPath('mppurchasemanagement/order/list')->getSize())) {
            $data[] = [
                'module_name' => 'Webkul_MpPurchaseManagement',
                'controller_path' => 'mppurchasemanagement/order/list',
                'label' => 'Marketplace Purchase Management',
                'is_child' => '0',
                'parent_id' => '0',
            ];
        }

        $this->moduleDataSetup->getConnection()
            ->insertMultiple($this->moduleDataSetup->getTable('marketplace_controller_list'), $data);

        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }
}
