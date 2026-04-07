<?php

namespace ADM\QuickDevBar\Plugin\Framework\App;

use ADM\QuickDevBar\Helper\Cookie;
use ADM\QuickDevBar\Service\AccessChecker;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;

class UpdateCookies
{
    private CookieManagerInterface $cookieManager;
    private CookieMetadataFactory $cookieMetadataFactory;
    private AccessChecker $accessChecker;

    public function __construct(
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        AccessChecker $accessChecker
    ) {
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->accessChecker = $accessChecker;
    }

    public function beforeDispatch(): void
    {
        if (!$this->accessChecker->isToolbarAccessAllowed()) {
            return;
        }

        $cookieValue = $this->cookieManager->getCookie(Cookie::COOKIE_NAME_PROFILER_ENABLED);
        if ($cookieValue) {
            //TODO: Update cookie lifetime
        }
    }
}
