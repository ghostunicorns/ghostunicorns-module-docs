<?php
/*
 * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\Docs\Model;

use Exception;
use GhostUnicorns\Docs\Model\ResourceModel\SetDocEntry;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Exception\LocalizedException;

class SetDoc
{
    /**
     * @var EncryptorInterface
     */
    private $encryptor;

    /**
     * @var SetDocEntry
     */
    private $setDocEntry;

    /**
     * @var GetUuid
     */
    private $getUuid;

    /**
     * @var ArchiveManager
     */
    private $archiveManager;

    /**
     * @param ArchiveManager $archiveManager
     * @param EncryptorInterface $encryptor
     * @param SetDocEntry $setDocEntry
     * @param GetUuid $getUuid
     */
    public function __construct(
        ArchiveManager $archiveManager,
        EncryptorInterface $encryptor,
        SetDocEntry $setDocEntry,
        GetUuid $getUuid
    ) {
        $this->encryptor = $encryptor;
        $this->setDocEntry = $setDocEntry;
        $this->getUuid = $getUuid;
        $this->archiveManager = $archiveManager;
    }

    /**
     * @param string $fileName
     * @param string $content
     * @param string $entityType
     * @param string $entityId
     * @throws LocalizedException
     */
    public function execute(string $fileName, string $content, string $entityType, string $entityId)
    {
        $encryptFileName = $this->encryptor->encrypt($fileName);
        $encryptContent = $this->encryptor->encrypt($content);
        $uuid = $this->getUuid->execute();

        //save encrypted docs
        $this->archiveManager->storeDoc($uuid, $encryptContent, $entityType);

        try {
            //save on db
            $this->setDocEntry->execute($uuid, $entityId, $entityType, $fileName, $encryptFileName);
        } catch (Exception $exception) {
            $this->archiveManager->deleteDoc($uuid, $entityType);
            throw new LocalizedException(__($exception->getMessage()));
        }
    }
}
