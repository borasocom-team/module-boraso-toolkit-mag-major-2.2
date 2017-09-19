<?php

namespace Boraso\Toolkit\Controller\Magento\UrlRewrite;

use Magento\UrlRewrite\Controller\Router;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

class RouterFix extends Router
{

    /**
     * @param string $requestPath
     * @param int    $storeId
     *
     * @return UrlRewrite|null
     */
    protected function getRewrite($requestPath, $storeId)
    {
        return $this->urlFinder->findOneByData([
            UrlRewrite::REQUEST_PATH => ltrim($requestPath, '/'),
            UrlRewrite::STORE_ID     => $storeId,
        ]);
    }
}