<?php

//namespace Boraso\Toolkit\Logger;
//
//use Boraso\Toolkit\Model\Logger\Handler\Debug;
//use Boraso\Toolkit\Model\Logger\Handler\Exception;
//use Boraso\Toolkit\Model\Logger\Handler\System;
//use Psr\Log\LoggerInterface;
//
//class Logger
//{
//
//    protected $psrlogger;
//    protected $debugHandler;
//    protected $exceptionHandler;
//    protected $systemHandler;
//    protected $logFileNamePrepend = '';
//
//    const SEPARATOR = '-------------------------------------------------';
//
//    public function __construct(
//        LoggerInterface $psrLogger,
//        Debug $debugHandler,
//        Exception $exceptionHandler,
//        System $systemHandler
//    ) {
//        $this->psrlogger        = $psrLogger;
//        $this->debugHandler     = $debugHandler;
//        $this->exceptionHandler = $exceptionHandler;
//        $this->systemHandler    = $systemHandler;
//    }
//
//    public function debugDump($message, $variableToDump)
//    {
//        if ( ! empty($variableToDump)) {
//            $this->psrlogger->debug(self::SEPARATOR);
//            $this->psrlogger->debug($message);
//            if (is_array($variableToDump)) {
//                $this->psrlogger->debug(print_r($variableToDump, true));
//            } else {
//                $this->psrlogger->debug($variableToDump);
//            }
//            $this->psrlogger->debug(self::SEPARATOR);
//        }
//
//        return false;
//    }
//
//    public function debugVarDump($message, $variableToDump)
//    {
//        if ( ! empty($variableToDump)) {
//            $this->psrlogger->debug(self::SEPARATOR);
//            $this->psrlogger->debug($message);
//            ob_start();
//            var_dump($variableToDump);
//            $this->psrlogger->debug(ob_get_clean());
//            $this->psrlogger->debug(self::SEPARATOR);
//        }
//
//        return false;
//    }
//
//    public function error($message)
//    {
//        $this->psrlogger->error(self::SEPARATOR);
//        $this->psrlogger->error($message);
//        $this->psrlogger->error(self::SEPARATOR);
//    }
//
//    public function log($message)
//    {
//        $this->psrlogger->log(self::SEPARATOR);
//        $this->psrlogger->log($message);
//        $this->psrlogger->log(self::SEPARATOR);
//    }
//
//    public function debug($message)
//    {
//        $this->logger->debug(self::SEPARATOR);
//        $this->logger->debug($message);
//        $this->logger->debug(self::SEPARATOR);
//    }
//
//    public function info($message)
//    {
//        $this->psrlogger->info(self::SEPARATOR);
//        $this->psrlogger->info($message);
//        $this->psrlogger->info(self::SEPARATOR);
//    }
//
//    public function errorTrace($message, Exception $exception)
//    {
//        $this->psrlogger->error(self::SEPARATOR);
//        $this->psrlogger->error($message);
//        $this->psrlogger->error($exception->getTrace());
//        $this->psrlogger->error(self::SEPARATOR);
//    }
//
//    public function setLogFileNamePrepend($prependString)
//    {
//        if ( ! empty($prependString)) {
//            $this->logFileNamePrepend = $prependString . '_';
//        }
//
//        $this->debugHandler->addPrependFileName($this->logFileNamePrepend);
//        $this->exceptionHandler->addPrependFileName($this->logFileNamePrepend);
//        $this->systemHandler->addPrependFileName($this->logFileNamePrepend);
//    }
//
//    public function getSystemLogFileName(){
//        return $this->systemHandler->getLogFileName();
//    }
//
//    public function getDebugLogFileName(){
//        return $this->debugHandler->getLogFileName();
//    }
//
//    public function getExceptionLogFileName(){
//        return $this->exceptionHandler->getLogFileName();
//    }
//
//}

namespace Boraso\Toolkit\Logger;
use \Monolog\Logger as MonoLog;

class Logger extends MonoLog
{
}