<?php

namespace Boraso\Toolkit\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    const SETTINGS_DEBUG_ENABLE = 'toolkit_settings/debug/enable';

    public function getSettingsDebugEnable()
    {
        return $this->scopeConfig->getValue(
            self::SETTINGS_DEBUG_ENABLE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}