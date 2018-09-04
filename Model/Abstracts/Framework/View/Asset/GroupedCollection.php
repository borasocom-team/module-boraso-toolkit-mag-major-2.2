<?php

namespace Boraso\Toolkit\Model\Abstracts\Framework\View\Asset;

class GroupedCollection extends \Magento\Framework\View\Asset\GroupedCollection{

    public function setGroups($groups){
        $this->groups = $groups;
    }

}