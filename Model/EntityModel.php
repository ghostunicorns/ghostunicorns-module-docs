<?php
/*
 * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\Docs\Model;

use Magento\Framework\Model\AbstractExtensibleModel;

class EntityModel extends AbstractExtensibleModel
{
    const CACHE_TAG = 'order_docs';
    protected $_cacheTag = 'order_docs';
    protected $_eventPrefix = 'order_docs';

    protected function _construct()
    {
        $this->_init(ResourceModel\EntityResourceModel::class);
    }
}
