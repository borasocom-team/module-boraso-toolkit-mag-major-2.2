<?php

namespace Boraso\Toolkit\Logger;

use Boraso\Toolkit\Logger\Handler\Exception;
use Monolog\Logger as MonoLog;

class Logger extends MonoLog
{

    protected $logFileNamePrepend = '';

    const SEPARATOR = '-------------------------------------------------';
    const SEPARATOR_START = '---------->START: ';
    const SEPARATOR_STOP = '---------->STOP: ';

    public function __construct($name, $handlers = array(), $processors = array())
    {
        parent::__construct($name, $handlers, $processors);
    }

    public function info($message, array $context = array())
    {
        parent::info($message, $context);
    }

    public function debug($messageOrArrayOrObject, array $context = array())
    {
        parent::debug(self::SEPARATOR, $context);
        if(is_array($messageOrArrayOrObject)){
            parent::debug(print_r($messageOrArrayOrObject,true), $context);
        }
        else if(method_exists($messageOrArrayOrObject,'getData')){
            parent::debug(print_r($messageOrArrayOrObject->getData(),true), $context);
        }
        else{
            parent::debug($messageOrArrayOrObject, $context);
        }
        parent::debug(self::SEPARATOR, $context);
    }

    public function error($message, array $context = array()){
        parent::error(self::SEPARATOR);
        parent::error($message);
        parent::error(self::SEPARATOR);
    }

    public function setLogFileNamePrepend($prependString)
    {
        if ( ! empty($prependString)) {
            $this->logFileNamePrepend = $prependString . '_';
        }

        foreach ($this->handlers as $handler) {
            $handler->addPrependFileName($this->logFileNamePrepend);
        }
    }

    public function start($message, array $context = array())
    {
        parent::info(self::SEPARATOR_START . $message, $context);
    }

    public function stop($message, array $context = array())
    {
        parent::info(self::SEPARATOR_STOP . $message, $context);
    }

}