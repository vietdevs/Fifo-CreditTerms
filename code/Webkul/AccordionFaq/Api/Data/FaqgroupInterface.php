<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_AccordionFaq
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\AccordionFaq\Api\Data;

interface FaqgroupInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ENTITY_ID    = 'id';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set ID
     *
     * @param int $id
     * @return \Webkul\AccordionFaq\Api\Data\FaqgroupInterface
     */
    public function setId($id);
}
