<?php
/*
 * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\Docs\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class EntityResourceModel extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('gu_docs', 'id');
    }
}
