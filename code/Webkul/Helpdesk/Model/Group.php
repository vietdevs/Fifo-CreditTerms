<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_Helpdesk
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\Helpdesk\Model;

use Webkul\Helpdesk\Api\Data\GroupInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * Helpdesk Group Model
 *
 * @method \Webkul\Helpdesk\Model\ResourceModel\Group _getResource()
 * @method \Webkul\Helpdesk\Model\ResourceModel\Group getResource()
 */
class Group extends \Magento\Framework\Model\AbstractModel implements GroupInterface, IdentityInterface
{
    /**
     * No route page id
     */
    const NOROUTE_ENTITY_ID = 'no-route';

    /**
     * Helpdesk Group cache tag
     */
    const CACHE_TAG = 'helpdesk_ticket_group';

    /**
     * @var string
     */
    protected $_cacheTag = 'helpdesk_ticket_group';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'helpdesk_ticket_group';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Webkul\Helpdesk\Model\ResourceModel\Group::class);
    }

    /**
     * Load object data
     *
     * @param int|null $id
     * @param string $field
     * @return $this
     */
    public function load($id, $field = null)
    {
        if ($id === null) {
            return $this->noRouteGroup();
        }
        return parent::load($id, $field);
    }

    /**
     * Load No-Route Group
     *
     * @return \Webkul\Helpdesk\Model\Group
     */
    public function noRouteGroup()
    {
        return $this->load(self::NOROUTE_ENTITY_ID, $this->getIdFieldName());
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getId()
    {
        return parent::getData(self::ENTITY_ID);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return \Webkul\Helpdesk\Api\Data\GroupInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    /**
     * toOptionArray Returns group option array
     * @return Array $data Group option array
     */
    public function toOptionArray()
    {
        $data =[];
        $groupCollection = $this->getCollection()
                                ->addFieldToFilter("is_active", ["eq"=>1]);
        foreach ($groupCollection as $group) {
            $data[] =  ['value'=>$group->getId(), 'label'=>$group->getGroupName()];
        }
        return  $data;
    }
}
