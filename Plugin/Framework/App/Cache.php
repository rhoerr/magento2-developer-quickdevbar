<?php

namespace ADM\QuickDevBar\Plugin\Framework\App;

use ADM\QuickDevBar\Service\AccessChecker;
use ADM\QuickDevBar\Service\App\Cache as CacheService;
use Magento\Framework\App\CacheInterface;

class Cache
{
    private CacheService $cacheService;
    private AccessChecker $accessChecker;

    public function __construct(
        CacheService $cacheService,
        AccessChecker $accessChecker
    ) {
        $this->cacheService = $cacheService;
        $this->accessChecker = $accessChecker;
    }

    /**
     * @param CacheInterface $subject
     * @param string $identifier
     */
    public function beforeLoad(CacheInterface $subject, string $identifier)
    {
        if (!$this->accessChecker->isToolbarAccessAllowed()) {
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
        if (!$this->accessChecker->isToolbarAccessAllowed()) {
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
        if (!$this->accessChecker->isToolbarAccessAllowed()) {
            return;
        }
        $this->cacheService->addCache('remove', $identifier);
    }
}
