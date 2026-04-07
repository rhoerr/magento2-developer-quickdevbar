<?php

namespace ADM\QuickDevBar\Plugin\Framework\Event;

use ADM\QuickDevBar\Service\AccessChecker;
use ADM\QuickDevBar\Service\Event\Manager as ServiceManager;

class Manager
{
    private ServiceManager $serviceManager;
    private AccessChecker $accessChecker;

    public function __construct(
        ServiceManager $serviceManager,
        AccessChecker $accessChecker
    ) {
        $this->serviceManager = $serviceManager;
        $this->accessChecker = $accessChecker;
    }

    /**
     * Record dispatched events for the dev bar profiler.
     *
     * @param \Magento\Framework\Event\ManagerInterface $interceptor
     * @param string $eventName
     * @param array $data
     */
    public function beforeDispatch($interceptor, $eventName, $data = [])
    {
        if (!$this->accessChecker->isToolbarAccessAllowed()) {
            return;
        }
        $this->serviceManager->addEvent($eventName, $data);
    }
}
