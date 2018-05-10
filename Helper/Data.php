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
    const ENABLE_SEND_EMAIL = 'newsletter/subscription/enable_send_email';
    const HIDE_CATEGORY_DESCRIPTION = 'catalog/category_customization/hide_category_description';

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

    /**
     * @param String $value
     *
     * @return mixed
     */
    public function getCssClassFromString(String $value){
        return str_replace(' ','-', preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($value)));
    }
    
    /**
     * @return mixed
     */
    public function getEnableSendEmail()
    {
        return $this->scopeConfig->getValue(
            self::ENABLE_SEND_EMAIL,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getHideCategoryDescription()
    {
        return $this->scopeConfig->getValue(
            self::HIDE_CATEGORY_DESCRIPTION,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}