<?php

namespace ADM\QuickDevBar\Plugin\Zend;

use ADM\QuickDevBar\Helper\Cookie;
use ADM\QuickDevBar\Helper\Data as QdbHelper;
use Zend_Db_Adapter_Abstract;

class DbAdapter
{
    private Cookie $cookieHelper;
    private QdbHelper $qdbHelper;

    public function __construct(
        Cookie $cookieHelper,
        QdbHelper $qdbHelper
    ) {
        $this->cookieHelper = $cookieHelper;
        $this->qdbHelper = $qdbHelper;
    }

    /**
     * @param Zend_Db_Adapter_Abstract $subject
     * @param array|bool|Zend_Config|Zend_Db_Profiler $profiler
     * @return array
     */
    public function beforeSetProfiler(Zend_Db_Adapter_Abstract $subject, $profiler): array
    {
        if ($this->qdbHelper->isToolbarAccessAllowed()
            && $this->cookieHelper->isProfilerEnabled()
        ) {
            $profiler = [
                'enabled' => 1,
                'class'   => \ADM\QuickDevBar\Profiler\Db::class,
            ];
        }

        return [$profiler];
    }
}
