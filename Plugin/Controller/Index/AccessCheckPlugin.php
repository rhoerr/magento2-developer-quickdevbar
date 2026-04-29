<?php

namespace ADM\QuickDevBar\Plugin\Controller\Index;

use ADM\QuickDevBar\Helper\AccessChecker;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NotFoundException;

class AccessCheckPlugin
{
    private AccessChecker $accessChecker;

    public function __construct(AccessChecker $accessChecker)
    {
        $this->accessChecker = $accessChecker;
    }

    public function beforeDispatch(Action $subject, RequestInterface $request): ?array
    {
        if (!$this->accessChecker->isToolbarAccessAllowed()) {
            throw new NotFoundException(__('Page not found.'));
        }

        return [$request];
    }
}
