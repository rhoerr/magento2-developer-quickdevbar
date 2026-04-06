<?php

namespace ADM\QuickDevBar\Plugin\Framework\Event;

use ADM\QuickDevBar\Helper\Data as QdbHelper;
use ADM\QuickDevBar\Service\Event\Manager as ServiceManager;

class Manager
{
    private ServiceManager $serviceManager;
    private QdbHelper $qdbHelper;
    private ?bool $isAllowed = null;

    public function __construct(
        ServiceManager $serviceManager,
        QdbHelper $qdbHelper
    ) {
        $this->serviceManager = $serviceManager;
        $this->qdbHelper = $qdbHelper;
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
        if ($this->isAllowed === null) {
            $this->isAllowed = $this->qdbHelper->isToolbarAccessAllowed();
        }
        if (!$this->isAllowed) {
            return;
        }
        $this->serviceManager->addEvent($eventName, $data);
    }
}
