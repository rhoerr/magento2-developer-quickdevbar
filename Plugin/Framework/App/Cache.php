<?php

namespace ADM\QuickDevBar\Plugin\Framework\App;

use ADM\QuickDevBar\Helper\Data as QdbHelper;
use ADM\QuickDevBar\Service\App\Cache as CacheService;
use Magento\Framework\App\CacheInterface;

class Cache
{
    private CacheService $cacheService;
    private QdbHelper $qdbHelper;
    private ?bool $isAllowed = null;

    public function __construct(
        CacheService $cacheService,
        QdbHelper $qdbHelper
    ) {
        $this->cacheService = $cacheService;
        $this->qdbHelper = $qdbHelper;
    }

    private function isAllowed(): bool
    {
        if ($this->isAllowed === null) {
            $this->isAllowed = $this->qdbHelper->isToolbarAccessAllowed();
        }
        return $this->isAllowed;
    }

    /**
     * @param CacheInterface $subject
     * @param string $identifier
     */
    public function beforeLoad(CacheInterface $subject, string $identifier)
    {
        if (!$this->isAllowed()) {
            return;
        }
        $this->cacheService->addCache('load', $identifier);
    }

    /**
     * @param CacheInterface $subject
     * @param string $data
     * @param string $identifier
     * @param array $tags
     * @param int|null $lifeTime
     */
    public function beforeSave(
        CacheInterface $subject,
        string $data,
        string $identifier,
        array $tags = [],
        $lifeTime = null
    ) {
        if (!$this->isAllowed()) {
            return;
        }
        $this->cacheService->addCache('save', $identifier);
    }

    /**
     * @param CacheInterface $subject
     * @param string $identifier
     */
    public function beforeRemove(CacheInterface $subject, string $identifier)
    {
        if (!$this->isAllowed()) {
            return;
        }
        $this->cacheService->addCache('remove', $identifier);
    }
}
