<?php

namespace ADM\QuickDevBar\Controller\Log;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;

class Reset extends \ADM\QuickDevBar\Controller\Index implements CsrfAwareActionInterface, HttpPostActionInterface
{
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
        $fileKey = $this->getRequest()->getParam('log_key', '');
        $output = '';

        $file = $this->_qdbHelper->getLogFiles($fileKey);
        if (!$file) {
            $output = 'Cannot find file.';
        } elseif (!empty($file['size'])) {
            $output = unlink($file['path']) ? 'File has been reset.' : 'Cannot reset file.';
        } else {
            $output = 'Cannot find file to reset.';
        }

        $this->_view->loadLayout();
        $resultRaw = $this->_resultRawFactory->create();
        // Control page caches (varnish, fastly, built-in php cache)
        $resultRaw->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0', true);

        return $resultRaw->setContents($output);
    }
}
