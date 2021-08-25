<?php
/*
 * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\Docs\Model;

use Magento\Framework\Exception\LocalizedException;

class DocsManager
{
    /**
     * @var GetDocs
     */
    private $getDocs;

    /**
     * @var SetDoc
     */
    private $setDoc;

    /**
     * @var RemoveDoc
     */
    private $removeDoc;

    /**
     * @param GetDocs $getDocs
     * @param SetDoc $setDoc
     * @param RemoveDoc $removeDoc
     */
    public function __construct(
        GetDocs $getDocs,
        SetDoc $setDoc,
        RemoveDoc $removeDoc
    ) {
        $this->getDocs = $getDocs;
        $this->setDoc = $setDoc;
        $this->removeDoc = $removeDoc;
    }

    /**
     * @param string $fileName
     * @param string $content
     * @param string $entityType
     * @param string $entityId
     * @throws LocalizedException
     */
    public function setNewDoc(string $fileName, string $content, string $entityType, string $entityId)
    {
        $this->setDoc->execute($fileName, $content, $entityType, $entityId);
    }

    /**
     * @param string $docId
     * @param string $entityType
     * @param string $entityId
     * @throws LocalizedException
     */
    public function removeDoc(string $docId, string $entityType, string $entityId)
    {
        $this->removeDoc->execute($docId, $entityType, $entityId);
    }

    /**
     * @param string $docId
     * @return array
     * @throws LocalizedException
     */
    public function getDocById(string $docId): array
    {
        return $this->getDocs->execute($docId);
    }
}
