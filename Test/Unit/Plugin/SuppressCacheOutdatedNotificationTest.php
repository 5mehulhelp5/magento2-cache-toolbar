<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\CacheToolbar\Test\Unit\Plugin;

use Magento\AdminNotification\Model\System\Message\CacheOutdated;
use Magento\Framework\AuthorizationInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pronko\CacheToolbar\Model\Config;
use Pronko\CacheToolbar\Plugin\SuppressCacheOutdatedNotification;

class SuppressCacheOutdatedNotificationTest extends TestCase
{
    /**
     * @var Config&MockObject
     */
    private Config $config;

    /**
     * @var AuthorizationInterface&MockObject
     */
    private AuthorizationInterface $authorization;

    /**
     * @var CacheOutdated&MockObject
     */
    private CacheOutdated $subject;

    private SuppressCacheOutdatedNotification $plugin;

    protected function setUp(): void
    {
        $this->config = $this->createMock(Config::class);
        $this->authorization = $this->getMockBuilder(AuthorizationInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject = $this->createMock(CacheOutdated::class);

        $this->plugin = new SuppressCacheOutdatedNotification(
            $this->config,
            $this->authorization
        );
    }

    public function testKeepsNativeMessageWhenToolbarDisabled(): void
    {
        $this->config->method('isEnabled')->willReturn(false);
        $this->authorization->expects($this->never())->method('isAllowed');

        $this->assertTrue($this->plugin->afterIsDisplayed($this->subject, true));
    }

    public function testKeepsNativeMessageWhenAdminLacksPermission(): void
    {
        $this->config->method('isEnabled')->willReturn(true);
        $this->authorization->method('isAllowed')
            ->with('Pronko_CacheToolbar::cache_clear')
            ->willReturn(false);

        $this->assertTrue($this->plugin->afterIsDisplayed($this->subject, true));
    }

    public function testSuppressesNativeMessageForPermittedAdmin(): void
    {
        $this->config->method('isEnabled')->willReturn(true);
        $this->authorization->method('isAllowed')
            ->with('Pronko_CacheToolbar::cache_clear')
            ->willReturn(true);

        $this->assertFalse($this->plugin->afterIsDisplayed($this->subject, true));
    }
}
