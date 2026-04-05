<?php

namespace ADM\QuickDevBar\Block\Tab\Content;

use Magento\Config\Model\Config\TypePool;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Config extends \ADM\QuickDevBar\Block\Tab\Panel
{
    private const MASK = '******';

    private const SENSITIVE_PATH_PREFIXES = [
        'crypt/',
        'payment/',
        'system/smtp/',
        'oauth/',
    ];

    private const SENSITIVE_PATH_SEGMENTS = [
        'password',
        'secret',
        'key',
        'token',
        'credential',
    ];

    protected $_config_values;
    protected $_appConfig;
    private TypePool $typePool;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Config $appConfig,
        \ADM\QuickDevBar\Helper\Data $qdbHelper,
        \ADM\QuickDevBar\Helper\Register $qdbHelperRegister,
        TypePool $typePool,
        array $data = []
    ) {
        $this->_appConfig = $appConfig;
        $this->typePool = $typePool;
        parent::__construct($context, $qdbHelper, $qdbHelperRegister, $data);
    }

    public function getTitleBadge()
    {
        return count($this->getConfigValues());
    }

    public function getConfigValues()
    {
        if ($this->_config_values === null) {
            $this->_config_values = [];
            $this->_buildFlatConfig($this->_appConfig->getValue());
        }
        return $this->_config_values;
    }

    protected function _buildFlatConfig($scope, $path = '')
    {
        if (is_array($scope)) {
            foreach ($scope as $key => $value) {
                $this->_buildFlatConfig($value, $path . ($path ? '/' : '') . $key);
            }
        } else {
            $maskedValue = $this->isSensitivePath($path) ? self::MASK : $scope;
            $this->_config_values[] = ['path' => $path, 'value' => $maskedValue];
        }
    }

    private function isSensitivePath(string $path): bool
    {
        if ($this->typePool->isPresent($path, TypePool::TYPE_SENSITIVE)
            || $this->typePool->isPresent($path, TypePool::TYPE_ENVIRONMENT)
        ) {
            return true;
        }

        foreach (self::SENSITIVE_PATH_PREFIXES as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return true;
            }
        }

        $segments = explode('/', $path);
        foreach ($segments as $segment) {
            foreach (self::SENSITIVE_PATH_SEGMENTS as $keyword) {
                if (str_contains($segment, $keyword)) {
                    return true;
                }
            }
        }

        return false;
    }
}
