<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Boraso\Toolkit\Logger\Handler;

use Monolog\Logger;
use Magento\Framework\Logger\Handler\Base;

/**
 * Class Error
 *
 * @package Boraso\Toolkit\Logger\Handler
 */
class Error extends Base
{
    /**
     * @var string
     */
    protected $fileName = '/var/log/error.log';

    /**
     * @var int
     */
    protected $loggerType = Logger::ERROR;

    /**
     * @param $prependString
     */
    public function addPrependFileName($prependString){
        $this->fileName = '/var/log/' . $prependString . 'error.log';
        $this->url = BP . $this->fileName;
    }

    /**
     * @return string
     */
    public function getLogFileName(){
        return $this->fileName;
    }
}
