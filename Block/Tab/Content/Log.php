<?php

namespace ADM\QuickDevBar\Block\Tab\Content;

use Magento\Framework\UrlInterface;

class Log extends \ADM\QuickDevBar\Block\Tab\Panel
{

    protected $_jsonHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \ADM\QuickDevBar\Helper\Data $qdbHelper
     * @param \ADM\QuickDevBar\Helper\Register $qdbHelperRegister
     * @param UrlInterface $frontUrl
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \ADM\QuickDevBar\Helper\Data $qdbHelper,
        \ADM\QuickDevBar\Helper\Register $qdbHelperRegister,
        UrlInterface $frontUrl,
        array $data = []
    ) {
        $this->_jsonHelper = $jsonHelper;

        parent::__construct($context, $qdbHelper, $qdbHelperRegister, $frontUrl, $data);
    }

    public function getTailLines()
    {
        return 20;
    }

    public function getLogFiles()
    {
        return $this->helper->getLogFiles();
    }

    public function getJsonLogFiles()
    {
        return $this->_jsonHelper->jsonEncode($this->helper->getLogFiles());
    }

}
