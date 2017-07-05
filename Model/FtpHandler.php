<?php

namespace Boraso\Toolkit\Model;

use Boraso\Toolkit\Model\Logger;
use Magento\Checkout\Exception;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Io\Ftp;

class FtpHandler
{

    protected $ftpHandler;
    protected $logger;

    public function __construct(Ftp $ftpHandler, Logger $logger)
    {
        $this->ftpHandler = $ftpHandler;
        $this->logger     = $logger;
    }

    protected function verifyConnectionParameters($connectionParameters)
    {
        if (empty($connectionParameters) || !is_array($connectionParameters)) {
            $this->logger->error('FTP connection verify: connection parameters is empty at all');

            return false;
        }
        if (is_null($connectionParameters['host']) || empty($connectionParameters['host'])) {
            $this->logger->error('FTP connection verify: host parameter is incorrect');

            return false;
        }
        if (is_null($connectionParameters['user']) || empty($connectionParameters['user'])) {
            $this->logger->error('FTP connection verify: user parameter is incorrect');

            return false;
        }
        if (is_null($connectionParameters['password']) || empty($connectionParameters['password'])) {
            $this->logger->error('FTP connection verify: password parameter is incorrect');

            return false;
        }
        if (is_null($connectionParameters['ssl']) || ! is_bool($connectionParameters['ssl'])) {
            $this->logger->error('FTP connection verify: ssl parameter is incorrect');

            return false;
        }
        if (is_null($connectionParameters['passive']) || ! is_bool($connectionParameters['passive'])) {
            $this->logger->error('FTP connection verify: passive parameter is incorrect');

            return false;
        }

        return true;
    }

    public function upload($connectionParameters, $fileLocalPath, $fileRemotePath)
    {
        if ( ! $this->verifyConnectionParameters($connectionParameters)) {
            $this->logger->error('FTP upload failed');

            return false;
        }

        try {
            $openedFtpConnection = $this->ftpHandler->open($connectionParameters);
            $fileContent = file_get_contents($fileLocalPath);
            $this->ftpHandler->write($fileRemotePath,$fileContent);
            $this->ftpHandler->close();
        } catch (Exception $exception) {
            $this->logger->errorTrace('Can\'t upload file',$exception);
        }
    }
}