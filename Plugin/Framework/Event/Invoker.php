<?php

namespace ADM\QuickDevBar\Plugin\Framework\Event;

use ADM\QuickDevBar\Helper\AccessChecker;
use ADM\QuickDevBar\Service\Observer as ServiceObserver;
use Magento\Framework\Event\Observer;

class Invoker
{
    private ServiceObserver $serviceObserver;
    private AccessChecker $accessChecker;

    public function __construct(
        ServiceObserver $serviceObserver,
        AccessChecker $accessChecker
    ) {
        $this->serviceObserver = $serviceObserver;
        $this->accessChecker = $accessChecker;
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
        if (!$this->accessChecker->isToolbarAccessAllowed()) {
            return;
        }
        if (isset($configuration['disabled']) && true === $configuration['disabled']) {
            return;
        }
        $this->serviceObserver->addObserver($configuration, $observer);
    }
}
