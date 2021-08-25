<?php
/*
 * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\Docs\Model;

use GhostUnicorns\Docs\Model\ResourceModel\GetDocsById;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem\Driver\File;

class GetDocs
{
    /**
     * @var GetDocsById
     */
    private $getDocsById;

    /**
     * @var EncryptorInterface
     */
    private $encryptor;

    /**
     * @var File
     */
    private $driverFile;

    /**
     * @var ArchiveManager
     */
    private $archiveManager;

    /**
     * @param GetDocsById $getDocsById
     * @param EncryptorInterface $encryptor
     * @param File $driverFile
     * @param ArchiveManager $archiveManager
     */
    public function __construct(
        GetDocsById $getDocsById,
        EncryptorInterface $encryptor,
        File $driverFile,
        ArchiveManager $archiveManager
    ) {
        $this->getDocsById = $getDocsById;
        $this->encryptor = $encryptor;
        $this->driverFile = $driverFile;
        $this->archiveManager = $archiveManager;
    }

    /**
     * @param string $docId
     * @return array
     * @throws LocalizedException
     */
    public function execute(string $docId): array
    {
        $doc = $this->getDocsById->execute($docId);

        if (empty($doc)) {
            return [];
        }

        if (!array_key_exists('entity_type', $doc) || $doc['entity_type'] === '') {
            return [];
        }

        if (!array_key_exists('uuid', $doc) || $doc['uuid'] === '') {
            return [];
        }

        $entityType = $doc['entity_type'];
        $uuid = $doc['uuid'];

        try {
            $filePath = $this->archiveManager->getCompletePath($entityType, $uuid);
            $content = $this->driverFile->fileGetContents($filePath);
            $doc['content'] = $this->encryptor->decrypt($content);
            $doc['content'] = base64_decode($doc['content']);
            return $doc;
        } catch (\Exception $exception) {
            throw new LocalizedException(__($exception->getMessage()));
        }
    }
}
