<?php

namespace ADM\QuickDevBar\Controller\Action;

use Magento\Framework\App\Action\HttpPostActionInterface;

class CacheCss extends \ADM\QuickDevBar\Controller\Index implements HttpPostActionInterface
{
    protected $_mergeService;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \ADM\QuickDevBar\Helper\Data $qdbHelper,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Framework\View\Asset\MergeService $mergeService
    ) {
        parent::__construct($context, $qdbHelper, $resultRawFactory, $layoutFactory);
        $this->_mergeService = $mergeService;
    }

    public function execute()
    {
        $output = '';

        try {
            $this->_mergeService->cleanMergedJsCss();
            $output = 'Cache merged Js and Css cleaned';
        } catch (\Throwable $e) {
            $output = $e->getMessage();
        }

        $resultRaw = $this->_resultRawFactory->create();
        return $resultRaw->setContents($output);
    }
}
