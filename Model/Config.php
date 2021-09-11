<?php
/*
 * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\Docs\Model;

use GhostUnicorns\Docs\Api\ConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config implements ConfigInterface
{
    /**
     * @var string
     */
    const DOCS_GENERAL_ENABLED = 'docs/general/enabled';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @inheirtDoc
     */
    public function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::DOCS_GENERAL_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @inheirtDoc
     */
    public function getAllowedExtension(): array
    {
        return [];
    }
}
