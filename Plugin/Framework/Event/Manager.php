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
