<?php

namespace ADM\QuickDevBar\Plugin\Search;

use ADM\QuickDevBar\Service\AccessChecker;

class SearchClient
{
    private AccessChecker $accessChecker;

    public function __construct(AccessChecker $accessChecker)
    {
        $this->accessChecker = $accessChecker;
    }

    public function beforeQuery(\Magento\OpenSearch\Model\SearchClient $subject, array $query)
    {
        if (!$this->accessChecker->isToolbarAccessAllowed()) {
            return;
        }
        return [$query];
    }
}
