<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\CacheToolbar\ViewModel;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Pronko\CacheToolbar\Model\Config;

class Toolbar implements ArgumentInterface
{
    public function __construct(
        private readonly Config $config,
        private readonly TypeListInterface $cacheTypeList,
        private readonly UrlInterface $url,
        private readonly AuthorizationInterface $authorization
    ) {}

    public function getInvalidatedTypes(): array
    {
        return array_keys($this->cacheTypeList->getInvalidated());
    }

    public function isOutdated(): bool
    {
        return !empty($this->getInvalidatedTypes());
    }

    public function isEnabled(): bool
    {
        return $this->config->isEnabled();
    }

    public function isShortcutEnabled(): bool
    {
        return $this->config->isShortcutEnabled();
    }

    public function isPromoEnabled(): bool
    {
        return $this->config->isPromoEnabled();
    }

    public function getSmartClearUrl(): string
    {
        return $this->url->getUrl('pronko/cache/smartclear');
    }

    public function getFullClearUrl(): string
    {
        return $this->url->getUrl('pronko/cache/fullclear');
    }

    public function canClear(): bool
    {
        return $this->authorization->isAllowed('Pronko_CacheToolbar::cache_clear');
    }

    public function getTypeLabels(): array
    {
        return $this->cacheTypeList->getTypeLabels();
    }
}
