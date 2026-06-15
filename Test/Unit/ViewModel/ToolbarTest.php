<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\CacheToolbar\Test\Unit\ViewModel;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\AuthorizationInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pronko\CacheToolbar\Model\Config;
use Pronko\CacheToolbar\ViewModel\Toolbar;

class ToolbarTest extends TestCase
{
    /**
     * @var Config&MockObject
     */
    private Config $config;

    /**
     * @var TypeListInterface&MockObject
     */
    private TypeListInterface $cacheTypeList;

    /**
     * @var UrlInterface&MockObject
     */
    private UrlInterface $url;

    /**
     * @var AuthorizationInterface&MockObject
     */
    private AuthorizationInterface $authorization;

    private Toolbar $object;

    protected function setUp(): void
    {
        $this->config = $this->createMock(Config::class);
        $this->cacheTypeList = $this->getMockBuilder(TypeListInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->url = $this->getMockBuilder(UrlInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->authorization = $this->getMockBuilder(AuthorizationInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = new Toolbar(
            $this->config,
            $this->cacheTypeList,
            $this->url,
            $this->authorization
        );
    }

    public function testGetInvalidatedTypesReturnsKeys(): void
    {
        $this->cacheTypeList->expects($this->once())
            ->method('getInvalidated')
            ->willReturn(['config' => 'x', 'layout' => 'y']);

        $this->assertSame(['config', 'layout'], $this->object->getInvalidatedTypes());
    }

    public function testIsOutdatedTrueWhenInvalidated(): void
    {
        $this->cacheTypeList->method('getInvalidated')
            ->willReturn(['config' => 'x']);

        $this->assertTrue($this->object->isOutdated());
    }

    public function testIsOutdatedFalseWhenNothingInvalidated(): void
    {
        $this->cacheTypeList->method('getInvalidated')
            ->willReturn([]);

        $this->assertFalse($this->object->isOutdated());
    }

    public function testIsEnabledDelegatesToConfig(): void
    {
        $this->config->expects($this->once())
            ->method('isEnabled')
            ->willReturn(true);

        $this->assertTrue($this->object->isEnabled());
    }

    public function testCanClearDelegatesToAuthorization(): void
    {
        $this->authorization->expects($this->once())
            ->method('isAllowed')
            ->with('Pronko_CacheToolbar::cache_clear')
            ->willReturn(false);

        $this->assertFalse($this->object->canClear());
    }

    public function testGetSmartClearUrl(): void
    {
        $this->url->expects($this->once())
            ->method('getUrl')
            ->with('pronko/cache/smartclear')
            ->willReturn('https://admin/pronko/cache/smartclear');

        $this->assertSame('https://admin/pronko/cache/smartclear', $this->object->getSmartClearUrl());
    }

    public function testGetFullClearUrl(): void
    {
        $this->url->expects($this->once())
            ->method('getUrl')
            ->with('pronko/cache/fullclear')
            ->willReturn('https://admin/pronko/cache/fullclear');

        $this->assertSame('https://admin/pronko/cache/fullclear', $this->object->getFullClearUrl());
    }

    public function testGetTypeLabels(): void
    {
        $labels = ['config' => 'Configuration', 'full_page' => 'Page Cache'];
        $this->cacheTypeList->expects($this->once())
            ->method('getTypeLabels')
            ->willReturn($labels);

        $this->assertSame($labels, $this->object->getTypeLabels());
    }
}
