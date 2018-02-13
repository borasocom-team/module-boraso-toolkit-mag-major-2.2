<?php

namespace Boraso\Toolkit\Model\Abstracts;

class PathItem{

    protected $target;
    protected $request;

    public function setTarget($target){
        if(is_string($target)){
            $this->target = $target;

            return $target;
        }

        return false;
    }

    public function setRequest($request){
        if(is_string($request)){
            $this->request = $request;

            return $request;
        }

        return false;
    }

    public function getTarget(){
        return $this->target;
    }

    public function getRequest(){
        return $this->request;
    }

}