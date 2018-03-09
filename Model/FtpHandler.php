<?php

namespace Boraso\Toolkit\Model;

use Boraso\Toolkit\Logger\Logger;
use Magento\Checkout\Exception;
use Magento\Framework\Filesystem\Io\Ftp;

/**
 * Class FtpHandler
 *
 * @package Boraso\Toolkit\Model
 */
class FtpHandler
{

    protected $ftpHandler;
    protected $logger;

    /**
     * FtpHandler constructor.
     *
     * @param Ftp    $ftpHandler
     * @param Logger $logger
     */
    public function __construct(Ftp $ftpHandler, Logger $logger)
    {
        $this->ftpHandler = $ftpHandler;
        $this->logger     = $logger;
    }

    /**
     * @param $connectionParameters
     *
     * @return bool
     */
    protected function verifyConnectionParameters($connectionParameters)
    {
        if (empty($connectionParameters) || ! is_array($connectionParameters)) {
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
        if (is_null($connectionParameters['ssl']) || ! in_array($connectionParameters['ssl'], array(0, 1))) {
            $this->logger->error('FTP connection verify: ssl parameter is incorrect');

            return false;
        }
        if (is_null($connectionParameters['passive']) || ! in_array($connectionParameters['ssl'], array(0, 1))) {
            $this->logger->error('FTP connection verify: passive parameter is incorrect');

            return false;
        }

        return true;
    }

    /**
     * @param $connectionParameters
     * @param $fileLocalPath
     * @param $fileRemotePath
     *
     * @return bool
     */
    public function upload($connectionParameters, $fileLocalPath, $fileRemotePath)
    {
        $openedFtpConnection = $this->openFtpConnection($connectionParameters);

        if ($openedFtpConnection) {
            try {
                $fileContent = file_get_contents($fileLocalPath);
                $writeResult = $this->ftpHandler->write($fileRemotePath, $fileContent);

                $this->logger->debug('FTP write result: ' . $writeResult);

                $this->ftpHandler->close();

                return true;
            } catch (\Exception $exception) {
                $this->logger->error('Can\'t upload file');
                $this->logger->debug($exception->getMessage());
            }
        }

        return false;
    }

    public function downloadFile($connectionParameters, $fileRemotePath, $fileLocalPath)
    {
        $openedFtpConnection = $this->openFtpConnection($connectionParameters);

        if ($openedFtpConnection) {
            try {
                $fileRead = ($this->ftpHandler->read($fileRemotePath, $fileLocalPath) === false) ? false : true;
                $this->logger->debug('FTP read: ' . $fileRemotePath . ' result: ' . $fileRead);

                $this->ftpHandler->close();

                return $fileRead;
            } catch (\Exception $exception) {
                $this->logger->error('Can\'t upload file');
                $this->logger->debug($exception->getMessage());
            }
        }

        return false;
    }

    public function downloadFilesFromDir($connectionParameters, $remotePath, $localPath, $startWith = '', $rename = false)
    {
        $openedFtpConnection = $this->openFtpConnection($connectionParameters);

        if ($openedFtpConnection) {
            $now = new \DateTime();
            try {
                $this->ftpHandler->cd($remotePath);
                $files = $this->ftpHandler->ls();
                foreach ($files as $file) {
                    $filename = $file['text'];
                    if (substr($filename, 0, 2) == './') {
                        $filename = substr($filename, 2, strlen($filename));
                    }
                    if (!empty($startWith)) {
                        $prefix = substr($filename, 0, strlen($startWith));
                        if ($prefix != $startWith) {
                            continue;
                        }
                    }
                    $fileLocalPath = $localPath . $filename;
                    if ($this->ftpHandler->read($filename, $fileLocalPath) == false) {
                        $this->logger->debug('FTP read: ' . $filename . ' failed!!!');
                        return false;
                    } else {
                        $this->logger->debug('FTP read: ' . $filename);
                        if ($rename) {
                            $newFileName = $now->format('Ymd_') . $filename;
                            if ($this->ftpHandler->mv($filename, $newFileName) == false) {
                                $this->logger->debug('FTP rename: ' . $filename . ' failed!!!');
                                return false;
                            } else {
                                $this->logger->debug('FTP file: ' . $filename . ' renamed to ' . $newFileName);
                            }
                        }
                        else {
                            if ($this->ftpHandler->rm($filename) == false) {
                                $this->logger->debug('FTP remove: ' . $filename . ' failed!!!');
                                return false;
                            } else {
                                $this->logger->debug('FTP file: ' . $filename . ' removed');
                            }
                        }
                    }
                }

                $this->ftpHandler->close();

                return true;
            } catch (\Exception $exception) {
                $this->logger->error('Can\'t upload file');
                $this->logger->debug($exception->getMessage());
                return false;
            }
        }
    }

    /**
     * @param $connectionParameters
     * @return bool|true
     */
    protected function openFtpConnection($connectionParameters)
    {
        if ( ! $this->verifyConnectionParameters($connectionParameters)) {
            $this->logger->error('FTP upload failed');

            return false;
        }

        try {
            return $this->ftpHandler->open($connectionParameters);
        } catch (\Exception $exception) {
            $this->logger->error('Can\'t open server connection');
            $this->logger->debug($exception->getMessage());
            return false;
        }
    }
}