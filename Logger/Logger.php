<?php

namespace Boraso\Toolkit\Logger;

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

    public function debug($message, array $context = array())
    {
        parent::debug(self::SEPARATOR, $context);
        parent::debug($message, $context);
        parent::debug(self::SEPARATOR, $context);
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