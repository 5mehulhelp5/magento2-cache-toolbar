<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\CacheToolbar\Controller\Adminhtml\Cache;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Pronko\CacheToolbar\Model\Config;

class SmartClear extends Action
{
    public const ADMIN_RESOURCE = 'Pronko_CacheToolbar::cache_clear';

    public function __construct(
        Context $context,
        private readonly JsonFactory $resultJsonFactory,
        private readonly Config $config,
        private readonly TypeListInterface $typeList,
    ) {
        parent::__construct($context);
    }

    public function execute(): Json
    {
        $start = microtime(true);
        $invalidated = array_keys($this->typeList->getInvalidated());
        $configured  = $this->config->getSmartClearTypes();
        $types       = array_values(array_intersect($configured, $invalidated));

        foreach ($types as $typeCode) {
            $this->typeList->cleanType($typeCode);
        }

        $elapsed = round(microtime(true) - $start, 1);

        return $this->resultJsonFactory->create()->setData([
            'success' => true,
            'message' => sprintf((string) __('Cache cleared (%d types in %ss)'), count($types), $elapsed),
            'types'   => count($types),
            'time'    => $elapsed . 's',
        ]);
    }
}
