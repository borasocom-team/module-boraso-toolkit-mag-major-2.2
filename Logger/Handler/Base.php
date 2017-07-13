<?php

namespace Boraso\Toolkit\Logger\Handler;

use Magento\Framework\Filesystem\DriverInterface;
use Magento\Framework\Logger\Handler\Base as MagentoBase;
use Monolog\Formatter\LineFormatter;

/**
 * Class Base
 *
 * @package Boraso\Toolkit\Logger\Handler
 */
class Base extends MagentoBase
{

    /**
     * Base constructor.
     *
     * @param DriverInterface $filesystem
     * @param null            $filePath
     */
    public function __construct(DriverInterface $filesystem, $filePath = null)
    {
        parent::__construct($filesystem, $filePath);

        $this->setFormatter(new LineFormatter(null, null, true, true));
    }
}