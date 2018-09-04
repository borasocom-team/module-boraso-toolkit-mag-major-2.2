<?php

namespace Boraso\Toolkit\Plugin\Framework\View\Page;

use Magento\Framework\View\Page\Config;

class ConfigPlugin
{

    const NO_MERGE_EXTENSIONS = 'woff,woff2';

    public function afterGetKeywords(Config $subject, $result)
    {
        return (string)null;
    }

    public function afterGetAssetCollection(Config $subject, $result)
    {
        $notToMergeTypes = explode(',',trim(self::NO_MERGE_EXTENSIONS));

        $resultFiltered = $result;

        $groups = $result->getGroups();

        $newGroups = array();
        foreach ($groups as $key => $group){
            if(in_array($group->getProperties()['content_type'],$notToMergeTypes)){
                $savedGroup = clone $group;
                foreach ($group->getAssets() as $path => $asset){
                    $newGroup = clone $savedGroup;
                    $newGroup->setAssets([$path => $asset]);
                    array_push($newGroups, $newGroup);
                }
                unset($groups[$key]);
            }
        }
        $finalGroups = array_merge($groups,$newGroups);

        $result->setGroups($finalGroups);

        return $resultFiltered;
    }
}