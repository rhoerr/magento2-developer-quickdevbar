<?php

namespace ADM\QuickDevBar\Plugin\Framework\App;

use ADM\QuickDevBar\Helper\Cookie;
use ADM\QuickDevBar\Helper\Data as QdbHelper;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;

class UpdateCookies
{
    private CookieManagerInterface $cookieManager;
    private CookieMetadataFactory $cookieMetadataFactory;
    private QdbHelper $qdbHelper;
    private ?bool $isAllowed = null;

    public function __construct(
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        QdbHelper $qdbHelper
    ) {
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->qdbHelper = $qdbHelper;
    }

    public function beforeDispatch(): void
    {
        if ($this->isAllowed === null) {
            $this->isAllowed = $this->qdbHelper->isToolbarAccessAllowed();
        }
        if (!$this->isAllowed) {
            return;
        }

        $cookieValue = $this->cookieManager->getCookie(Cookie::COOKIE_NAME_PROFILER_ENABLED);
        if ($cookieValue) {
            //TODO: Update cookie lifetime
        }
    }
}
