<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\CacheToolbar\Plugin;

use Magento\AdminNotification\Model\System\Message\CacheOutdated;
use Magento\Framework\AuthorizationInterface;
use Pronko\CacheToolbar\Model\Config;

class SuppressCacheOutdatedNotification
{
    public function __construct(
        private readonly Config $config,
        private readonly AuthorizationInterface $authorization
    ) {}

    public function afterIsDisplayed(CacheOutdated $subject, bool $result): bool
    {
        if (!$this->config->isEnabled()) {
            return $result;
        }

        if (!$this->authorization->isAllowed('Pronko_CacheToolbar::cache_clear')) {
            return $result;
        }

        return false;
    }
}
