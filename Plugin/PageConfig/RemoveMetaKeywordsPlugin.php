<?php

namespace Boraso\Toolkit\Plugin\PageConfig;

use Magento\Framework\View\Page\Config;

class RemoveMetaKeywordsPlugin {

    public function afterGetKeywords(Config $subject, $result)
    {
        return (string)null;
    }
}