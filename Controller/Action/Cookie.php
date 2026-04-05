<?php

namespace ADM\QuickDevBar\Controller\Action;

use ADM\QuickDevBar\Helper\Cookie as CookieHelper;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;

class Cookie extends \ADM\QuickDevBar\Controller\Index implements CsrfAwareActionInterface, HttpPostActionInterface
{
    private const ALLOWED_COOKIES = [
        CookieHelper::COOKIE_NAME_PROFILER_ENABLED,
        CookieHelper::COOKIE_NAME_PROFILER_BACKTRACE_ENABLED,
        'qdb_appearance',
    ];

    const COOKIE_DURATION = 8640000;

    private \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager;
    private \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory;
    private \Magento\Framework\Session\Config $sessionConfig;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \ADM\QuickDevBar\Helper\Data $qdbHelper,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory,
        \Magento\Framework\Session\Config $sessionConfig
    ) {
        parent::__construct($context, $qdbHelper, $resultRawFactory, $layoutFactory);
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->sessionConfig = $sessionConfig;
    }

    /**
     * @inheritDoc
     */
    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return null;
    }

    public function execute()
    {
        $output = '';

        try {
            $cookieName = $this->getRequest()->getParam('qdbName');

            if (!in_array($cookieName, self::ALLOWED_COOKIES, true)) {
                throw new \InvalidArgumentException('Cookie name not allowed: ' . $cookieName);
            }

            $cookieValue = $this->getRequest()->getParam('qdbValue');
            $cookieToggle = $this->getRequest()->getParam('qdbToggle');

            if ($cookieValue === null) {
                if ($cookieToggle) {
                    $cookieValue = $this->cookieManager->getCookie($cookieName) ? null : true;
                } else {
                    throw new \InvalidArgumentException('No value to set');
                }
            }

            $metadata = $this->cookieMetadataFactory->createPublicCookieMetadata();
            $metadata->setPath($this->sessionConfig->getCookiePath());
            $metadata->setDomain($this->sessionConfig->getCookieDomain());
            $metadata->setDuration($this->sessionConfig->getCookieLifetime());
            $metadata->setSecure($this->sessionConfig->getCookieSecure());
            $metadata->setHttpOnly($this->sessionConfig->getCookieHttpOnly());
            $metadata->setSameSite($this->sessionConfig->getCookieSameSite());

            $this->cookieManager->setPublicCookie(
                $cookieName,
                $cookieValue,
                $metadata
            );

            $output = $cookieName . ':' . $cookieValue;
        } catch (\Throwable $e) {
            $output = $e->getMessage();
        }

        $resultRaw = $this->_resultRawFactory->create();
        return $resultRaw->setContents($output);
    }
}
