<?php

namespace Boraso\Toolkit\Model\FlatFile;

use Boraso\Toolkit\Logger\Logger;
use Braintree\Exception;
use Magento\Framework\Filesystem\Driver\File;

class Handler
{

    const NEWLINE_BYTES = 2;

    protected $file;
    protected $structure;
    protected $linePointer = 0;
    protected $path;
    protected $elementsNumber;
    protected $logger;

    public function __construct(File $file, Logger $logger)
    {
        $this->file   = $file;
        $this->logger = $logger;
    }

    protected function checkConsistency()
    {

        if (empty($this->structure) || empty($this->path)) {
            return false;
        }

        if ( ! $this->file->isFile($this->path) || ! $this->file->isReadable($this->path)) {
            return false;
        }

        return true;
    }

    public function init(string $path, array $elements)
    {
        foreach ($elements as $element) {
            if ((count($element) == 3) && ! empty($element['name']) && ! empty($element['length'])) {
                $this->structure[] = array(
                    'name'     => $element['name'],
                    'length'   => $element['length'],
                    'fillWith' => $element['fillWith']
                );
            } else {
                $this->structure = array();

                return false;
            }
        }

        if ( ! empty($path)) {
            $this->path = $path;
        } else {
            return false;
        }

        $this->elementsNumber = count($this->structure);

        return $this->elementsNumber;
    }

    public function readLine($resource = false)
    {
        if ( ! $this->checkConsistency()) {
            return false;
        }

        if ( ! $resource) {
            $close = true;
            try {
                $resource = $this->file->fileOpen($this->path, 'r');
            } catch (Exception $exception) {
                $this->logger->debug('Failed to open file ' . $this->path);
                $this->logger->debug($exception->getMessage());
                $this->logger->debug($exception->getTraceAsString());
            }
        }

        try {
            $dataLine = fgets($resource);
            $this->logger->debug($dataLine);
        } catch (Exception $exception) {
            $this->logger->debug('Failed to read line from ' . $this->path);
            $this->logger->debug($exception->getMessage());
            $this->logger->debug($exception->getTraceAsString());
        }


        if ( ! isset($dataLine) || empty($dataLine) || !$dataLine) {
            $this->logger->debug('empty data line');
            return false;
        }

        $this->linePointer++;

        $readedLinePortion = 0;
        $data = array();
        foreach ($this->structure as $item) {
            $data[$item['name']] = substr($dataLine, $readedLinePortion, $item['length']);
            $readedLinePortion   += $item['length'];
        }

        if (isset($close) && $close) {
            $this->file->fileClose($resource);
        }

        $this->logger->debug($data);

        return $data;
    }

    public function readLines()
    {
        if ( ! $this->checkConsistency()) {
            return false;
        }

        try {
            $resource = $this->file->fileOpen($this->path, 'r');
        } catch (Exception $exception) {
            $this->logger->debug($exception->getMessage());
            $this->logger->debug($exception->getTraceAsString());
        }

        $data = array();

        $dataLine = true;
        while ($dataLine != false) {
            $dataLine = $this->readLine($resource);
            if(is_array($dataLine)){
                array_push($data, $dataLine);
            }
        }

        $this->file->fileClose($resource);

        return $data;
    }
}