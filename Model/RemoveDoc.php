<?php
/*
 * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\Docs\Model;

use Exception;
use GhostUnicorns\Docs\Model\ResourceModel\GetDocsByEntity;
use GhostUnicorns\Docs\Model\ResourceModel\RemoveDocEntry;
use GhostUnicorns\Docs\Model\ResourceModel\SetDocEntry;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Exception\LocalizedException;

class RemoveDoc
{
    /**
     * @var ArchiveManager
     */
    private $archiveManager;

    /**
     * @var RemoveDocEntry
     */
    private $removeDocEntry;

    /**
     * @var GetDocsByEntity
     */
    private $getDocsByEntity;

    /**
     * @param ArchiveManager $archiveManager
     * @param RemoveDocEntry $removeDocEntry
     * @param GetDocsByEntity $getDocsByEntity
     */
    public function __construct(
        ArchiveManager $archiveManager,
        RemoveDocEntry $removeDocEntry,
        GetDocsByEntity $getDocsByEntity
    ) {
        $this->archiveManager = $archiveManager;
        $this->removeDocEntry = $removeDocEntry;
        $this->getDocsByEntity = $getDocsByEntity;
    }

    /**
     * @param string $docId
     * @param string $entityType
     * @param string $entityId
     * @throws LocalizedException
     */
    public function execute(string $docId, string $entityType, string $entityId)
    {
        try {
            $docs = $this->getDocsByEntity->execute($entityId, $entityType);

            foreach ($docs as $doc) {
                if ($doc['id'] === $docId) {
                    //remove encrypted docs
                    $this->archiveManager->deleteDoc($doc['uuid'], $entityType);
                    //remove record on db
                    $this->removeDocEntry->execute($docId);
                }
            }
        } catch (Exception $exception) {
            throw new LocalizedException(__($exception->getMessage()));
        }
    }
}
