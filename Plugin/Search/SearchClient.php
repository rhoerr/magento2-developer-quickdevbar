<?php

namespace ADM\QuickDevBar\Plugin\Search;

use ADM\QuickDevBar\Helper\Data as QdbHelper;

class SearchClient
{
    private QdbHelper $qdbHelper;
    private ?bool $isAllowed = null;

    public function __construct(QdbHelper $qdbHelper)
    {
        $this->qdbHelper = $qdbHelper;
    }

    public function beforeQuery(\Magento\OpenSearch\Model\SearchClient $subject, array $query)
    {
        if ($this->isAllowed === null) {
            $this->isAllowed = $this->qdbHelper->isToolbarAccessAllowed();
        }
        if (!$this->isAllowed) {
            return;
        }
        return [$query];
    }
}
