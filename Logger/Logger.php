<?php

namespace Boraso\Toolkit\Logger;

use Boraso\Toolkit\Logger\Handler\Exception;
use Monolog\Logger as MonoLog;
use Boraso\Toolkit\Helper\Data;

/**
 * Class Logger
 *
 * @package Boraso\Toolkit\Logger
 */
class Logger extends MonoLog
{

    protected $logFileNamePrepend = '';
    protected $helper;

    const SEPARATOR = '-------------------------------------------------';
    const SEPARATOR_START = '---------->START: ';
    const SEPARATOR_STOP = '---------->STOP: ';

    /**
     * Logger constructor.
     *
     * @param string $name
     * @param array  $handlers
     * @param Data   $helper
     * @param array  $processors
     */
    public function __construct($name, $handlers = array(), Data $helper, $processors = array())
    {
        parent::__construct($name, $handlers, $processors);

        $this->helper = $helper;
    }

    /**
     * @param string $message
     * @param array  $context
     */
    public function info($message, array $context = array())
    {
        parent::info($message, $context);
    }

    /**
     * @param string|array $messageOrArrayOrObject
     * @param array $context
     * @param bool $forceLog
     * @param bool $multipleMessages
     */
    public function debug($messageOrArrayOrObject, array $context = array(), $forceLog = false, $multipleMessages = false)
    {
        if($this->helper->getSettingsDebugEnable() || $forceLog){
            parent::debug(self::SEPARATOR, $context);
            if(is_array($messageOrArrayOrObject)){
                if($multipleMessages){
                    foreach ($messageOrArrayOrObject as $message){
                        parent::debug($message, $context);
                    }
                }
                else{
                    parent::debug(print_r($messageOrArrayOrObject,true), $context);
                }
            }
            else{
                parent::debug($messageOrArrayOrObject, $context);
            }
            parent::debug(self::SEPARATOR, $context);
        }
    }

    /**
     * @param string $message
     * @param array  $context
     */
    public function error($message, array $context = array()){
        parent::error(self::SEPARATOR);
        parent::error($message);
        parent::error(self::SEPARATOR);
    }

    /**
     * @param $prependString
     */
    public function setLogFileNamePrepend($prependString)
    {
        if ( ! empty($prependString)) {
            $this->logFileNamePrepend = $prependString . '_';
        }

        foreach ($this->handlers as $handler) {
            $handler->addPrependFileName($this->logFileNamePrepend);
        }
    }

    /**
     * @param       $message
     * @param array $context
     */
    public function start($message, array $context = array())
    {
        parent::info(self::SEPARATOR_START . $message, $context);
    }

    /**
     * @param       $message
     * @param array $context
     */
    public function stop($message, array $context = array())
    {
        parent::info(self::SEPARATOR_STOP . $message, $context);
    }

    /**
     * @param       $object
     * @param array $context
     * @param bool  $forceLog
     */
    public function debugObjectType($object, array $context = array(), $forceLog = false){
        if($this->helper->getSettingsDebugEnable() || $forceLog){
            parent::debug(self::SEPARATOR, $context);
            parent::debug(get_class($object));
            parent::debug(self::SEPARATOR, $context);
        }
    }

}