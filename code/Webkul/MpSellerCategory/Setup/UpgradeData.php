<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_MpSellerCategory
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\MpSellerCategory\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Webkul\Marketplace\Model\ControllersRepository;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var ControllersRepository
     */
    private $controllersRepository;

    /**
     * @param ControllersRepository $controllersRepository
     * @param EavSetupFactory       $eavSetupFactory
     */
    public function __construct(
        ControllersRepository $controllersRepository
    ) {
        $this->controllersRepository = $controllersRepository;
    }
    /**
     * {@inheritdoc}
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        /**
         * insert MpSellerCategory controller's data
         */
        $data = [];

        if (!count($this->controllersRepository->getByPath('mpsellercategory/category/manage'))) {
            $data[] = [
                'module_name' => 'Webkul_MpSellerCategory',
                'controller_path' => 'mpsellercategory/category/manage',
                'label' => 'Manage Categories',
                'is_child' => '0',
                'parent_id' => '0',
            ];
        }

        $setup->getConnection()->insertMultiple($setup->getTable('marketplace_controller_list'), $data);
        $setup->endSetup();
    }
}
