<?php
declare(strict_types=1);

namespace Pronko\CacheToolbar\Controller\Adminhtml\Cache;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\Json;

class Status extends Action
{
    public const ADMIN_RESOURCE = 'Pronko_CacheToolbar::cache_clear';

    public function __construct(
        Context $context,
        private readonly JsonFactory $resultJsonFactory,
        private readonly TypeListInterface $typeList
    ) {
        parent::__construct($context);
    }

    public function execute(): Json
    {
        $invalidated = $this->typeList->getInvalidated();
        $typeCodes = array_keys($invalidated);

        return $this->resultJsonFactory->create()->setData([
            'outdated' => !empty($typeCodes),
            'types'    => $typeCodes,
        ]);
    }
}
