<?php
namespace ADM\QuickDevBar\Block\Adminhtml\System\Config\Form\Fieldset;

use ADM\QuickDevBar\Helper\AccessChecker;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\HTTP\Header as HttpHeader;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;

class IsEnabled extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @var AccessChecker
     */
    protected $accessChecker;

    /**
     * @var RemoteAddress
     */
    protected $remoteAddress;

    /**
     * @var HttpHeader
     */
    protected $httpHeader;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        AccessChecker $accessChecker,
        RemoteAddress $remoteAddress,
        HttpHeader $httpHeader,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->accessChecker = $accessChecker;
        $this->remoteAddress = $remoteAddress;
        $this->httpHeader = $httpHeader;
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $html = [];
        if ($this->accessChecker->isToolbarAccessAllowed(true)) {
            $html[] = __('Yep');
        } else {
            $html[] = '<strong>' . __('Nope') .'</strong>';
            if (!$this->accessChecker->isIpAuthorized()) {
                $html[] =  __('Your Ip "<i class="note">%1</i>" is not allowed, you should register it in the field below.', $this->remoteAddress->getRemoteAddress());
            }
            if (!$this->accessChecker->isUserAgentAuthorized()) {
                $html[] =  __('Your User Agent "<i class="note">%1</i>" is not allowed, you should add a user-agent pattern', $this->httpHeader->getHttpUserAgent(true));
            }
        }


        return implode('<br/>', $html);
    }
}
