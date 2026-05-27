<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\CacheToolbar\Controller\Adminhtml\Cache;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Cache\Frontend\Pool;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Psr\Log\LoggerInterface;
use Throwable;

class FullClear extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Pronko_CacheToolbar::cache_clear';

    public function __construct(
        Context $context,
        private readonly JsonFactory $resultJsonFactory,
        private readonly TypeListInterface $typeList,
        private readonly Pool $cacheFrontendPool,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct($context);
    }

    public function execute(): Json
    {
        $start = microtime(true);
        $types = array_keys($this->typeList->getTypes());

        try {
            foreach ($types as $typeCode) {
                $this->typeList->cleanType($typeCode);
            }

            foreach ($this->cacheFrontendPool as $cacheFrontend) {
                $cacheFrontend->getBackend()->clean();
            }
        } catch (Throwable $e) {
            $this->logger->error('CacheToolbar FullClear failed: ' . $e->getMessage());
            return $this->resultJsonFactory->create()->setData([
                'success' => false,
                'message' => (string) __('Cache clear failed. Please try again.'),
            ]);
        }

        $elapsed = round(microtime(true) - $start, 1);

        return $this->resultJsonFactory->create()->setData([
            'success' => true,
            'message' => sprintf((string) __('All cache cleared (%d types in %ss)'), count($types), $elapsed),
            'types'   => count($types),
            'time'    => $elapsed . 's',
        ]);
    }
}
