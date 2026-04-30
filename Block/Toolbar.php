<?php

namespace ADM\QuickDevBar\Block;

use ADM\QuickDevBar\Helper\AccessChecker;
use ADM\QuickDevBar\Helper\Data;
use ADM\QuickDevBar\Block\Tab;
use Magento\Framework\App\ObjectManager;

class Toolbar extends \Magento\Framework\View\Element\Template
{
    protected $_mainTabs;

    protected $_qdnHelper;
    
    /**
     * @var AccessChecker
     */
    protected $accessChecker;
    /**
     * @var \Magento\Framework\Url|mixed
     */
    private  $_frontUrl;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        Data $qdnHelper,
        AccessChecker $accessChecker,
        array $data = []
    ) {

        $this->_qdnHelper = $qdnHelper;
        $this->accessChecker = $accessChecker;

        parent::__construct($context, $data);
    }

    /**
     * Determine if action is allowed
     *
     * @return bool
     */
    protected function canDisplay()
    {
        return $this->accessChecker->isToolbarAccessAllowed() && $this->_qdnHelper->isToolbarAreaAllowed($this->getArea());
    }

    public function getAppearance()
    {
        return $this->_qdnHelper->defaultAppearance();
    }

    public function getBaseUrl()
    {
        if ($this->_frontUrl === null) {
            $this->_frontUrl = ObjectManager::getInstance()->get('Magento\Framework\Url');
        }

        return $this->_frontUrl->getUrl();
    }

    public function isAjaxLoading()
    {
        return $this->_qdnHelper->isAjaxLoading() ? "true" : "false";
    }

    public function toHtml()
    {
        return (!$this->canDisplay()) ? '' : parent::toHtml();
    }
}
