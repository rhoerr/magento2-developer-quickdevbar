<?php

namespace ADM\QuickDevBar\Controller\Action;

use Magento\Framework\App\Action\HttpPostActionInterface;

class Cache extends \ADM\QuickDevBar\Controller\Index implements HttpPostActionInterface
{
    public function execute()
    {
        $error = false;
        $output = '';
        $ctrlMsg = $this->_qdbHelper->getControllerMessage();

        try {
            $cacheFrontendPool = $this->_qdbHelper->getCacheFrontendPool();
            $this->_eventManager->dispatch('adminhtml_cache_flush_all');
            foreach ($cacheFrontendPool as $cacheFrontend) {
                $cacheFrontend->clean();
                $cacheFrontend->getBackend()->clean();
            }
            $output = 'Cache cleaned';
        } catch (\Throwable $e) {
            $output = $e->getMessage();
            $error = true;
        }

        if ($ctrlMsg) {
            $output = $ctrlMsg . '<br/>' . $output;
        }

        $resultRaw = $this->_resultRawFactory->create();
        return $resultRaw->setContents($output);
    }
}
