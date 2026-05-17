<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\CacheToolbar\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    private const XML_PATH_ENABLED     = 'cache_toolbar/general/enabled';
    private const XML_PATH_SHORTCUT    = 'cache_toolbar/general/shortcut_enabled';
    private const XML_PATH_SMART_TYPES = 'cache_toolbar/smart_clear/types';
    private const XML_PATH_PROMO       = 'cache_toolbar/promo/enabled';

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {}

    public function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLED);
    }

    public function isShortcutEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_SHORTCUT);
    }

    public function getSmartClearTypes(): array
    {
        $value = $this->scopeConfig->getValue(self::XML_PATH_SMART_TYPES);
        return $value ? explode(',', $value) : [];
    }

    public function isPromoEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_PROMO);
    }
}
