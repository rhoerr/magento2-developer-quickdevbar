<?php

namespace ADM\QuickDevBar\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\State;
use Magento\Framework\HTTP\Header as HttpHeader;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\App\MaintenanceMode;

class AccessChecker
{
    private State $appState;

    private ScopeConfigInterface $scopeConfig;

    private RemoteAddress $remoteAddress;

    private HttpHeader $httpHeader;

    private ?bool $isAllowed = null;

    private static bool $isResolving = false;

    private MaintenanceMode $maintenanceMode;

    /**
     * @param State $appState
     * @param ScopeConfigInterface $scopeConfig
     * @param RemoteAddress $remoteAddress
     * @param MaintenanceMode $maintenanceMode
     * @param HttpHeader $httpHeader
     */
    public function __construct(
        State $appState,
        ScopeConfigInterface $scopeConfig,
        RemoteAddress $remoteAddress,
        MaintenanceMode $maintenanceMode,
        HttpHeader $httpHeader
    ) {
        $this->appState = $appState;
        $this->scopeConfig = $scopeConfig;
        $this->remoteAddress = $remoteAddress;
        $this->httpHeader = $httpHeader;
        $this->maintenanceMode = $maintenanceMode;
    }

    public function isToolbarAccessAllowed(bool $testWithRestriction = false): bool
    {
        // Re-entrancy guard: this method reads config which may trigger
        // cache/DB/event operations that loop back through our plugins.
        // During bootstrap, deny access rather than recurse.
        if (self::$isResolving) {
            return false;
        }

        if ($this->isAllowed !== null && !$testWithRestriction) {
            return $this->isAllowed;
        }

        self::$isResolving = true;
        try {
            $result = $this->resolve($testWithRestriction);
        } finally {
            self::$isResolving = false;
        }

        if (!$testWithRestriction) {
            $this->isAllowed = $result;
        }

        return $result;
    }

    private function resolve(bool $testWithRestriction): bool
    {
        if ($this->appState->getMode() === State::MODE_PRODUCTION) {
            if(!$this->maintenanceMode->isOn() || $this->maintenanceMode->isOn($this->remoteAddress->getRemoteAddress())) {
                return false;
            }
        }

        $enable = $this->getQdbConfig('enable');
        if (!$enable && !$testWithRestriction) {
            return false;
        }

        if ($enable > 1 || $testWithRestriction) {
            return $this->isIpAuthorized() || $this->isUserAgentAuthorized();
        }
        return true;
    }

    public function isIpAuthorized(): bool
    {
        return in_array($this->remoteAddress->getRemoteAddress(), $this->getAllowedIps(), true);
    }

    private function getAllowedIps(): array
    {
        $allowedIps = $this->getQdbConfig('allow_ips');
        if ($allowedIps) {
            $allowedIps = preg_split('#\s*,\s*#', $allowedIps, -1, PREG_SPLIT_NO_EMPTY);
        } else {
            $allowedIps = [];
        }

        return array_merge(['127.0.0.1', '::1'], $allowedIps);
    }

    public function isUserAgentAuthorized(): bool
    {
        $toolbarHeader = $this->getQdbConfig('toolbar_header');

        return !empty($toolbarHeader)
            && preg_match('/' . preg_quote($toolbarHeader, '/') . '/', $this->httpHeader->getHttpUserAgent(true));
    }

    private function getQdbConfig(string $key): mixed
    {
        return $this->scopeConfig->getValue('dev/quickdevbar/' . $key);
    }
}
