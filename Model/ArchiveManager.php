<?php
/*
 * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\Docs\Model;

use Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;
use Psr\Log\LoggerInterface;

class ArchiveManager
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var DirectoryList
     */
    private $directoryList;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var File
     */
    private $file;

    /**
     * @param Filesystem $filesystem
     * @param File $file
     * @param DirectoryList $directoryList
     * @param LoggerInterface $logger
     */
    public function __construct(
        Filesystem $filesystem,
        File $file,
        DirectoryList $directoryList,
        LoggerInterface $logger
    ) {
        $this->filesystem = $filesystem;
        $this->directoryList = $directoryList;
        $this->logger = $logger;
        $this->file = $file;
    }

    /**
     * @param string $fileName
     * @param string $encryptContent
     * @param string $folderName
     * @throws LocalizedException
     */
    public function storeDoc(string $fileName, string $encryptContent, string $folderName)
    {
        try {
            $varDir = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
            $filePath = $this->getCompletePath($folderName, $fileName);
            $varDir->writeFile($filePath, $encryptContent);
        } catch (Exception $e) {
            $phrase = __('Error during store a doc, exception: %1', $e->getMessage());
            $this->logger->error($phrase);
            throw new LocalizedException($phrase);
        }
    }

    /**
     * @param string $fileName
     * @param string $folderName
     * @throws LocalizedException
     */
    public function deleteDoc(string $fileName, string $folderName)
    {
        try {
            $filePath = $this->getCompletePath($folderName, $fileName);
            $this->file->deleteFile($filePath);
        } catch (Exception $e) {
            $phrase = __('Error deleting a customer doc, exception: %1', $e->getMessage());
            $this->logger->error($phrase);
            throw new LocalizedException($phrase);
        }
    }

    /**
     * @param string $folderName
     * @param string $fileName
     * @return string
     * @throws LocalizedException
     * @throws FileSystemException
     */
    public function getCompletePath(string $folderName, string $fileName): string
    {
        $filePath = $this->directoryList->getPath($this->directoryList::VAR_DIR) .
            DIRECTORY_SEPARATOR .
            'docs' .
            DIRECTORY_SEPARATOR .
            $folderName .
            DIRECTORY_SEPARATOR .
            $fileName;

        //security check
        $checkFileName = strpos($fileName, '/') || strpos($fileName, '\\');
        if ($checkFileName !== false) {
            $phrase = __('Invalid path provided: %1', $filePath);
            $this->logger->error($phrase);
            throw new LocalizedException($phrase);
        }

        return $filePath;
    }
}
