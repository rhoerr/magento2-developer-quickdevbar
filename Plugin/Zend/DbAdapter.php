<?php

namespace ADM\QuickDevBar\Plugin\Zend;

use ADM\QuickDevBar\Helper\Cookie;
use ADM\QuickDevBar\Service\AccessChecker;
use Zend_Db_Adapter_Abstract;

class DbAdapter
{
    private Cookie $cookieHelper;
    private AccessChecker $accessChecker;

    public function __construct(
        Cookie $cookieHelper,
        AccessChecker $accessChecker
    ) {
        $this->cookieHelper = $cookieHelper;
        $this->accessChecker = $accessChecker;
    }

    /**
     * @param Zend_Db_Adapter_Abstract $subject
     * @param array|bool|Zend_Config|Zend_Db_Profiler $profiler
     * @return array
     */
    public function beforeSetProfiler(Zend_Db_Adapter_Abstract $subject, $profiler): array
    {
        if ($this->cookieHelper->isProfilerEnabled()
            && $this->accessChecker->isToolbarAccessAllowed()
        ) {
            $profiler = [
                'enabled' => 1,
                'class'   => \ADM\QuickDevBar\Profiler\Db::class,
            ];
        }

        return [$profiler];
    }
}
