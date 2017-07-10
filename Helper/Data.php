<?php

namespace Boraso\Toolkit\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    const DEBUG_ENABLE = 'DEBUG_SETTINGS_ENABLE';
    
    public function getDebugEnable() {
        return Mage::getStoreConfig(self::DEBUG_ENABLE);
    }
}