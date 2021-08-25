<?php
/*
 * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\Docs\Model\ResourceModel;

use Magento\Framework\App\ResourceConnection;

class SetDocEntry
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
     * @param string $id
     * @param string $entityId
     * @param string $entityType
     * @param string $fileName
     * @param string $encryptFileName
     */
    public function execute(string $id, string $entityId, string $entityType, string $fileName, string $encryptFileName)
    {
        $connection = $this->resourceConnection->getConnection();
        $tableName = $this->resourceConnection->getTablePrefix() .
            $this->resourceConnection->getTableName('gu_docs');

        $connection->insert(
            $tableName,
            [
                'uuid' => $id,
                'entity_id' => $entityId,
                'entity_type' => $entityType,
                'file_name' => $fileName,
                'file_name_hash' => $encryptFileName,
            ]
        );
    }
}
