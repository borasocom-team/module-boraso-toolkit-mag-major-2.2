<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Boraso\Toolkit\Logger\Handler;

use Magento\Framework\Filesystem\DriverInterface;
use Monolog\Logger;
use Magento\Framework\Logger\Handler\Base;

/**
 * Class System
 *
 * @package Boraso\Toolkit\Logger\Handler
 */
class System extends Base
{
    /**
     * @var string
     */
    protected $fileName = '/var/log/system.log';

    /**
     * @var int
     */
    protected $loggerType = Logger::INFO;

    /**
     * @var Exception
     */
    protected $exceptionHandler;

    /**
     * @param DriverInterface $filesystem
     * @param Exception $exceptionHandler
     * @param string $filePath
     */
    public function __construct(
        DriverInterface $filesystem,
        Exception $exceptionHandler,
        $filePath = null
    ) {
        $this->exceptionHandler = $exceptionHandler;
        parent::__construct($filesystem, $filePath);
    }

    /**
     * @{inheritDoc}
     *
     * @param $record array
     * @return void
     */
    public function write(array $record)
    {
        if (isset($record['context']['is_exception']) && $record['context']['is_exception']) {
            unset($record['context']['is_exception']);
            $this->exceptionHandler->handle($record);
        } else {
            unset($record['context']['is_exception']);
            $record['formatted'] = $this->getFormatter()->format($record);
            parent::write($record);
        }
    }

    /**
     * @param $prependString
     */
    public function addPrependFileName($prependString){
        $this->fileName = '/var/log/' . $prependString . 'system.log';
        $this->url = BP . $this->fileName;
    }

    /**
     * @return string
     */
    public function getLogFileName(){
        return $this->fileName;
    }
}
