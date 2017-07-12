<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Boraso\Toolkit\Logger\Handler;

use Monolog\Logger;
use Magento\Framework\Logger\Handler\Base;

/**
 * Class Exception
 *
 * @package Boraso\Toolkit\Logger\Handler
 */
class Exception extends Base
{
    /**
     * @var string
     */
    protected $fileName = '/var/log/exception.log';

    /**
     * @var int
     */
    protected $loggerType = Logger::INFO;

    /**
     * @param $prependString
     */
    public function addPrependFileName($prependString){
        $this->fileName = '/var/log/' . $prependString . 'exception.log';
        $this->url = BP . $this->fileName;
    }

    /**
     * @return string
     */
    public function getLogFileName(){
        return $this->fileName;
    }
}
