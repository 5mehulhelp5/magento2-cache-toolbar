<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\CacheToolbar\Test\Unit\Model\Source;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\DataObject;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pronko\CacheToolbar\Model\Source\CacheTypes;

class CacheTypesTest extends TestCase
{
    /**
     * @var TypeListInterface&MockObject
     */
    private TypeListInterface $typeList;

    private CacheTypes $object;

    protected function setUp(): void
    {
        $this->typeList = $this->getMockBuilder(TypeListInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = new CacheTypes($this->typeList);
    }

    public function testToOptionArrayMapsTypesToValueLabelPairs(): void
    {
        $config = new DataObject(['id' => 'config', 'cache_type' => 'Configuration']);
        $fpc = new DataObject(['id' => 'full_page', 'cache_type' => 'Page Cache']);

        $this->typeList->expects($this->once())
            ->method('getTypes')
            ->willReturn(['config' => $config, 'full_page' => $fpc]);

        $this->assertSame(
            [
                ['value' => 'config', 'label' => 'Configuration'],
                ['value' => 'full_page', 'label' => 'Page Cache'],
            ],
            $this->object->toOptionArray()
        );
    }

    public function testToOptionArrayReturnsEmptyWhenNoTypes(): void
    {
        $this->typeList->expects($this->once())
            ->method('getTypes')
            ->willReturn([]);

        $this->assertSame([], $this->object->toOptionArray());
    }
}
