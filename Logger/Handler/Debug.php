<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Boraso\Toolkit\Logger\Handler;

use Monolog\Logger;
use Magento\Framework\Logger\Handler\Base;

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

    public function addPrependFileName($prependString){
        $this->fileName = '/var/log/' . $prependString . 'debug.log';
    }

    public function getLogFileName(){
        return $this->fileName;
    }
}
