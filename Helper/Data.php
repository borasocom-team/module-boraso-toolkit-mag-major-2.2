<?php

namespace Boraso\Toolkit\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Class Data
 *
 * @package Boraso\Toolkit\Helper
 */
class Data extends AbstractHelper
{
    const SETTINGS_DEBUG_ENABLE = 'toolkit_settings/debug/enable';

    /**
     * @return mixed
     */
    public function getSettingsDebugEnable()
    {
        return $this->scopeConfig->getValue(
            self::SETTINGS_DEBUG_ENABLE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}