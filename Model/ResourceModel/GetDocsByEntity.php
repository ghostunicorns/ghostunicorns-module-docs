<?php
/*
 * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\Docs\Model\ResourceModel;

use Magento\Framework\App\ResourceConnection;

class GetDocsByEntity
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @param string $entityId
     * @param string $entityType
     * @return array
     */
    public function execute(string $entityId, string $entityType): array
    {
        $connection = $this->resourceConnection->getConnection();
        $tableName = $this->resourceConnection->getTablePrefix() .
            $this->resourceConnection->getTableName('gu_docs');

        $where = $connection->quoteInto('entity_id = ?', $entityId) .
            $connection->quoteInto(' and entity_type = ?', $entityType);

        $qry = $connection->select()
            ->from($tableName)
            ->where($where);

        return $connection->fetchAll($qry);
    }
}
