<?php

namespace ADM\QuickDevBar\Block;

use ADM\QuickDevBar\Block\Tab;
use Magento\Framework\UrlInterface;

class Toolbar extends \Magento\Framework\View\Element\Template
{
    protected $_mainTabs;

    protected $_qdnHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \ADM\QuickDevBar\Helper\Data $qdnHelper
     * @param UrlInterface $frontUrl
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \ADM\QuickDevBar\Helper\Data $qdnHelper,
        private readonly UrlInterface $frontUrl,
        array $data = []
    ) {
        $this->_qdnHelper = $qdnHelper;

        parent::__construct($context, $data);
    }

    /**
     * Determine if action is allowed
     *
     * @return bool
     */
    protected function canDisplay()
    {
        return $this->_qdnHelper->isToolbarAccessAllowed() && $this->_qdnHelper->isToolbarAreaAllowed($this->getArea());
    }

    public function getAppearance()
    {
        return $this->_qdnHelper->defaultAppearance();
    }

    public function getBaseUrl()
    {
        return $this->frontUrl->getUrl();
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
