<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\CacheToolbar\Model\Source;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Data\OptionSourceInterface;

class CacheTypes implements OptionSourceInterface
{
    public function __construct(
        private readonly TypeListInterface $typeList
    ) {}

    public function toOptionArray(): array
    {
        $options = [];
        foreach ($this->typeList->getTypes() as $type) {
            $options[] = [
                'value' => $type->getId(),
                'label' => $type->getCacheType(),
            ];
        }
        return $options;
    }
}
