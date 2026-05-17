<?php
declare(strict_types=1);

namespace Pronko\CacheToolbar\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\App\Cache\TypeListInterface;
use Pronko\CacheToolbar\Model\Config;

class Toolbar extends Template
{
    protected $_template = 'Pronko_CacheToolbar::toolbar.phtml';

    public function __construct(
        Context $context,
        private readonly Config $config,
        private readonly TypeListInterface $cacheTypeList,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

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

    public function getPollingInterval(): int
    {
        return $this->config->getPollingInterval();
    }

    public function isPromoEnabled(): bool
    {
        return $this->config->isPromoEnabled();
    }

    public function getSmartClearUrl(): string
    {
        return $this->getUrl('pronko/cache/smartclear');
    }

    public function getFullClearUrl(): string
    {
        return $this->getUrl('pronko/cache/fullclear');
    }

    public function getStatusUrl(): string
    {
        return $this->getUrl('pronko/cache/status');
    }

    public function canClear(): bool
    {
        return $this->_authorization->isAllowed('Pronko_CacheToolbar::cache_clear');
    }
}
