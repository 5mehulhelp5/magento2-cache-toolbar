<?php
declare(strict_types=1);

namespace Pronko\CacheToolbar\Plugin;

use Magento\AdminNotification\Model\System\Message\CacheOutdated;
use Pronko\CacheToolbar\Model\Config;

class SuppressCacheOutdatedNotification
{
    public function __construct(
        private readonly Config $config
    ) {}

    public function afterIsDisplayed(CacheOutdated $subject, bool $result): bool
    {
        if (!$result) {
            return false;
        }

        return !$this->config->isEnabled();
    }
}
