<?php

namespace ADM\QuickDevBar\Plugin\PageCache\FrontController;

use ADM\QuickDevBar\Service\AccessChecker;
use ADM\QuickDevBar\Service\App\Cache as CacheService;
use Magento\PageCache\Model\Cache\Type as PageCache;

class BuiltinPlugin
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
     * @param PageCache $subject
     * @param string $identifier
     */
    public function beforeLoad(PageCache $subject, string $identifier)
    {
        if (!$this->accessChecker->isToolbarAccessAllowed()) {
            return;
        }
        $this->cacheService->addCache('load', $identifier);
    }

    /**
     * @param PageCache $subject
     * @param string $data
     * @param string $identifier
     * @param array $tags
     * @param int|null $lifeTime
     */
    public function beforeSave(
        PageCache $subject,
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
     * @param PageCache $subject
     * @param string $identifier
     */
    public function beforeRemove(PageCache $subject, string $identifier)
    {
        if (!$this->accessChecker->isToolbarAccessAllowed()) {
            return;
        }
        $this->cacheService->addCache('remove', $identifier);
    }
}
