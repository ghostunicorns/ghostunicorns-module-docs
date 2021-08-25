<?php
/*
 * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\Docs\Model\ResourceModel;

use Magento\Framework\App\ResourceConnection;

class GetDocsById
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
     * @param string $docId
     * @return array
     */
    public function execute(string $docId): array
    {
        $connection = $this->resourceConnection->getConnection();
        $tableName = $this->resourceConnection->getTablePrefix() .
            $this->resourceConnection->getTableName('gu_docs');

        $where = $connection->quoteInto('id = ?', $docId);

        $qry = $connection->select()
            ->from($tableName)
            ->where($where);

        $docs = $connection->fetchAll($qry);

        if (array_key_exists(0, $docs)) {
            return $docs[0];
        } else {
            return [];
        }
    }
}
