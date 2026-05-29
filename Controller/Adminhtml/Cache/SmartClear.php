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
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Pronko\CacheToolbar\Model\Config;
use Psr\Log\LoggerInterface;
use Throwable;

class SmartClear extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Pronko_CacheToolbar::cache_clear';

    public function __construct(
        Context $context,
        private readonly JsonFactory $resultJsonFactory,
        private readonly Config $config,
        private readonly TypeListInterface $typeList,
        private readonly LoggerInterface $logger,
        private readonly EventManagerInterface $eventManager,
    ) {
        parent::__construct($context);
    }

    public function execute(): Json
    {
        $start = microtime(true);
        $invalidated = array_keys($this->typeList->getInvalidated());
        $configured  = $this->config->getSmartClearTypes();
        $types       = array_values(array_intersect($configured, $invalidated));

        try {
            foreach ($types as $typeCode) {
                $this->typeList->cleanType($typeCode);
            }
        } catch (Throwable $e) {
            $this->logger->error('CacheToolbar SmartClear failed: ' . $e->getMessage());
            return $this->resultJsonFactory->create()->setData([
                'success' => false,
                'message' => (string) __('Cache clear failed. Please try again.'),
            ]);
        }

        $elapsed = round(microtime(true) - $start, 1);

        $this->eventManager->dispatch('pronko_cache_toolbar_clear_after', [
            'action'      => 'smart_clear',
            'cache_types' => $types,
            'duration_ms' => (int) round($elapsed * 1000),
            'origin'      => 'toolbar',
        ]);

        return $this->resultJsonFactory->create()->setData([
            'success' => true,
            'message' => sprintf((string) __('Cache cleared (%d types in %ss)'), count($types), $elapsed),
            'types'   => count($types),
            'time'    => $elapsed . 's',
        ]);
    }
}
