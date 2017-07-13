<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Boraso\Toolkit\Logger\Handler;

use Monolog\Logger;
use Boraso\Toolkit\Logger\Handler\Base;

/**
 * Class Debug
 *
 * @package Boraso\Toolkit\Logger\Handler
 */
class Debug extends Base
{
    /**
     * @var string
     */
    protected $fileName = '/var/log/debug.log';

    /**
     * @var int
     */
    protected $loggerType = Logger::DEBUG;

    /**
     * @param $prependString
     */
    public function addPrependFileName($prependString){
        $this->fileName = '/var/log/' . $prependString . 'debug.log';
        $this->url = BP . $this->fileName;
    }

    /**
     * @return string
     */
    public function getLogFileName(){
        return $this->fileName;
    }
}
