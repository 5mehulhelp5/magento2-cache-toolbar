<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\CacheToolbar\Test\Unit\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pronko\CacheToolbar\Model\Config;

class ConfigTest extends TestCase
{
    private const XML_PATH_ENABLED     = 'pronko_cache_toolbar/general/enabled';
    private const XML_PATH_SHORTCUT    = 'pronko_cache_toolbar/general/shortcut_enabled';
    private const XML_PATH_SMART_TYPES = 'pronko_cache_toolbar/smart_clear/types';
    private const XML_PATH_PROMO       = 'pronko_cache_toolbar/promo/enabled';

    /**
     * @var ScopeConfigInterface&MockObject
     */
    private ScopeConfigInterface $scopeConfig;

    private Config $object;

    protected function setUp(): void
    {
        $this->scopeConfig = $this->getMockBuilder(ScopeConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = new Config($this->scopeConfig);
    }

    public function testIsEnabled(): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('isSetFlag')
            ->with(self::XML_PATH_ENABLED)
            ->willReturn(true);

        $this->assertTrue($this->object->isEnabled());
    }

    public function testIsShortcutEnabled(): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('isSetFlag')
            ->with(self::XML_PATH_SHORTCUT)
            ->willReturn(false);

        $this->assertFalse($this->object->isShortcutEnabled());
    }

    public function testIsPromoEnabled(): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('isSetFlag')
            ->with(self::XML_PATH_PROMO)
            ->willReturn(true);

        $this->assertTrue($this->object->isPromoEnabled());
    }

    public function testGetSmartClearTypes(): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('getValue')
            ->with(self::XML_PATH_SMART_TYPES)
            ->willReturn('config,layout,full_page');

        $this->assertSame(['config', 'layout', 'full_page'], $this->object->getSmartClearTypes());
    }

    public function testGetSmartClearTypesReturnsEmptyArrayWhenUnset(): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('getValue')
            ->with(self::XML_PATH_SMART_TYPES)
            ->willReturn(null);

        $this->assertSame([], $this->object->getSmartClearTypes());
    }
}
