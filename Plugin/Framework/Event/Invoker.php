<?php

namespace ADM\QuickDevBar\Plugin\Framework\Event;

use ADM\QuickDevBar\Helper\Data as QdbHelper;
use ADM\QuickDevBar\Service\Observer as ServiceObserver;
use Magento\Framework\Event\Observer;

class Invoker
{
    private ServiceObserver $serviceObserver;
    private QdbHelper $qdbHelper;
    private ?bool $isAllowed = null;

    public function __construct(
        ServiceObserver $serviceObserver,
        QdbHelper $qdbHelper
    ) {
        $this->serviceObserver = $serviceObserver;
        $this->qdbHelper = $qdbHelper;
    }

    /**
     * Record observer invocations for the dev bar profiler.
     *
     * @param \Magento\Framework\Event\InvokerInterface $class
     * @param array $configuration
     * @param Observer $observer
     */
    public function beforeDispatch(
        \Magento\Framework\Event\InvokerInterface $class,
        array $configuration,
        Observer $observer
    ) {
        if ($this->isAllowed === null) {
            $this->isAllowed = $this->qdbHelper->isToolbarAccessAllowed();
        }
        if (!$this->isAllowed) {
            return;
        }
        if (isset($configuration['disabled']) && true === $configuration['disabled']) {
            return;
        }
        $this->serviceObserver->addObserver($configuration, $observer);
    }
}
