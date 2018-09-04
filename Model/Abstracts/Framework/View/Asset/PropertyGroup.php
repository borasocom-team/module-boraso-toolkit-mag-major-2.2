<?php

namespace Boraso\Toolkit\Model\Abstracts\Framework\View\Asset;

class PropertyGroup extends \Magento\Framework\View\Asset\PropertyGroup
{

    public function setAssets($assets)
    {
        $this->assets = $assets;
    }

    public function getAssets()
    {
        return $this->assets;
    }

}