<?php
/*
 * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\Docs\Model\ResourceModel\Entity;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use GhostUnicorns\DocsOrder\Model\EntityModel;
use GhostUnicorns\DocsOrder\Model\ResourceModel\EntityResourceModel;

class EntityCollection extends AbstractCollection
{
    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'docs_collection';
    protected $_eventObject = 'entity_collection';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(EntityModel::class, EntityResourceModel::class);
    }
}
