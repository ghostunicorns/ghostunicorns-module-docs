<?php
/*
 * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\Docs\Model;

use Exception;
use GhostUnicorns\Docs\Api\ConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;
use Magento\MediaStorage\Model\File\UploaderFactory;

class SetDocWithTmpFile
{
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var DocsManager
     */
    private $docsManager;

    /**
     * @var File
     */
    private $driverFile;

    /**
     * @var UploaderFactory
     */
    private $uploaderFactory;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param ConfigInterface $config
     * @param DocsManager $docsManager
     * @param File $driverFile
     * @param UploaderFactory $uploaderFactory
     * @param Filesystem $filesystem
     */
    public function __construct(
        ConfigInterface $config,
        DocsManager $docsManager,
        File $driverFile,
        UploaderFactory $uploaderFactory,
        Filesystem $filesystem
    ) {
        $this->config = $config;
        $this->docsManager = $docsManager;
        $this->driverFile = $driverFile;
        $this->uploaderFactory = $uploaderFactory;
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $entityType
     * @param string $entityId
     * @param array $file
     * @throws LocalizedException
     */
    public function execute(string $entityType, string $entityId, array $file)
    {
        list($destinationPath, $result) = $this->createTmpFile();
        if (!$result) {
            throw new LocalizedException(
                __('File cannot be saved to path: $1', $destinationPath)
            );
        }
        $this->saveCryptedFile($result, $file['name'], $entityType, $entityId);
    }

    /**
     * @return array
     * @throws Exception
     */
    private function createTmpFile(): array
    {
        $uploaderFactory = $this->uploaderFactory->create(['fileId' => 'upload_file']);
        $uploaderFactory->setAllowedExtensions($this->config->getAllowedExtension());
        $uploaderFactory->setAllowRenameFiles(true);
        $uploaderFactory->setFilesDispersion(true);
        $varDir = $this->filesystem->getDirectoryRead(DirectoryList::VAR_DIR);
        $destinationPath = $varDir->getAbsolutePath('tmp/docs');
        $result = $uploaderFactory->save($destinationPath);
        return [$destinationPath, $result];
    }

    /**
     * @param array $result
     * @param string $name
     * @param string $entityType
     * @param string $entityId
     * @throws LocalizedException
     * @throws FileSystemException
     */
    private function saveCryptedFile(array $result, string $name, string $entityType, string $entityId): void
    {
        $fileFullPath = $result['path'] . $result['file'];
        $content = $this->driverFile->fileGetContents($fileFullPath);
        $this->docsManager->setNewDoc($name, base64_encode($content), $entityType, $entityId);
        $this->driverFile->deleteFile($fileFullPath);
    }
}
