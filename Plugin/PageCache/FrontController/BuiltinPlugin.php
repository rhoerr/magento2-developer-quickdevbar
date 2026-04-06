<?php

namespace ADM\QuickDevBar\Plugin\PageCache\FrontController;

use ADM\QuickDevBar\Helper\Data as QdbHelper;
use ADM\QuickDevBar\Service\App\Cache as CacheService;
use Magento\PageCache\Model\Cache\Type as PageCache;

class BuiltinPlugin
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
     * @param PageCache $subject
     * @param string $identifier
     */
    public function beforeLoad(PageCache $subject, string $identifier)
    {
        if (!$this->isAllowed()) {
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
        if (!$this->isAllowed()) {
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
        if (!$this->isAllowed()) {
            return;
        }
        $this->cacheService->addCache('remove', $identifier);
    }
}
