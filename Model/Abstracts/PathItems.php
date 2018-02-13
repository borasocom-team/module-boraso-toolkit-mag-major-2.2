<?php

namespace Boraso\Toolkit\Model\Abstracts;

class PathItems
{

    /*
     * Must be an array of paths
     * path item must made as follow:
     * array(
     *  'name' => array(
     *      self::TARGET_PATH_KEY => 'target path value'
     *      self::REQUEST_PATH_KEY => 'request path value'
     *  )
     * )
     */
    protected $pathItems;

    public function __construct()
    {
        $this->pathItems = array();
    }

    public function addItems(array $paths)
    {
        foreach ($paths as $path) {
            $this->addItem($path);
        }
    }

    public function addItem(PathItem $path)
    {
        array_push($this->pathItems, $path);
    }

    public function getPaths(){
        return $this->pathItems;
    }

}

