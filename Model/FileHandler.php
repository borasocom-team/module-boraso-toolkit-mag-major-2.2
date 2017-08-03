<?php

namespace Boraso\Toolkit\Model;

use Magento\Framework\Filesystem\Io\File;

/**
 * Class FileHandler
 *
 * @package Boraso\Toolkit\Model
 */
class FileHandler extends File
{

    /**
     * @var \DateTime
     */
    protected $now;

    /**
     * FileHandler constructor.
     */
    public function __construct()
    {
        $this->now = new \DateTime();
    }

    /**
     * @param $fileFullPath
     *
     * @return bool|string
     */
    public function archive($fileFullPath, $archiveDirectory = false)
    {
        if (empty($fileFullPath) || ! is_file($fileFullPath)) {
            return false;
        }

        $filePathParts        = pathinfo($fileFullPath);
        if(!$archiveDirectory){
            $archiveDirectory     = $filePathParts['dirname'] . '/' . 'archive_' . $filePathParts['filename'];
        }
        $archivedFileFullPath = $archiveDirectory . '/' .$this->now->format('Ymd_') . $filePathParts['basename'];
        if ( ! is_dir($archiveDirectory)) {
            $this->mkdir($archiveDirectory, 0775);
        }
        $this->mv($fileFullPath, $archivedFileFullPath);

        return $archivedFileFullPath;
    }

}