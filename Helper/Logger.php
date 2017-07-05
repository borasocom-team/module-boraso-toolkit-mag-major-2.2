<?php

namespace Boraso\Toolkit\Helper;

use Braintree\Exception;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Psr\Log\LoggerInterface;

class Logger extends AbstractHelper
{

    protected $psrlogger;

    const SEPARATOR = '-------------------------------------------------';

    public function __construct(Context $context, LoggerInterface $psrLogger)
    {
        $this->psrlogger = $psrLogger;

        parent::__construct($context);
    }

    public function debugDump($message, $variableToDump)
    {
        if ( ! empty($variableToDump)) {
            $this->psrlogger->debug(self::SEPARATOR);
            $this->psrlogger->debug($message);
            if (is_array($variableToDump)) {
                $this->psrlogger->debug(print_r($variableToDump, true));
            } else {
                $this->psrlogger->debug($variableToDump);
            }
            $this->psrlogger->debug(self::SEPARATOR);
        }

        return false;
    }

    public function debugVarDump($message, $variableToDump)
    {
        if ( ! empty($variableToDump)) {
            $this->psrlogger->debug(self::SEPARATOR);
            $this->psrlogger->debug($message);
            ob_start();
            var_dump($variableToDump);
            $this->psrlogger->debug(ob_get_clean());
            $this->psrlogger->debug(self::SEPARATOR);
        }

        return false;
    }

    public function error($message){
        $this->psrlogger->error(self::SEPARATOR);
        $this->psrlogger->error($message);
        $this->psrlogger->error(self::SEPARATOR);
    }

    public function errorTrace($message, Exception $exception){
        $this->psrlogger->error(self::SEPARATOR);
        $this->psrlogger->error($message);
        $this->psrlogger->error($exception->getTrace());
        $this->psrlogger->error(self::SEPARATOR);
    }

}