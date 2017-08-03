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

    protected function checkConsistency($write = false)
    {

        if (empty($this->structure) || empty($this->path)) {
            return false;
        }

        if ($this->file->isFile($this->path)) {
            if ($write) {
                if ( ! $this->file->isWritable($this->path)) {
                    return false;
                }
            } else {
                if ( ! $this->file->isReadable($this->path)) {
                    return false;
                }
            }
        } else {
            if ($write) {
                if ( ! $this->file->isWritable(dirname($this->path))) {
                    return false;
                }
            } else {
                return false;
            }
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

    /**
     * @param bool $resource
     *
     * @return array|bool
     */
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

        if ( ! isset($dataLine) || empty($dataLine) || ! $dataLine) {
            $this->logger->debug('empty data line');

            return false;
        }

        $this->linePointer++;

        $readedLinePortion = 0;
        $data              = array();
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
            if (is_array($dataLine)) {
                array_push($data, $dataLine);
            }
        }

        $this->file->fileClose($resource);

        return $data;
    }

    /**
     * @param array $data
     * data array to write
     * must have the same elements number as structure or the method will complete le line using structure info
     *
     * @param bool  $resource
     *
     * @return array|bool
     */
    public function writeLine(array $data, $resource = false)
    {
        if ( ! $this->checkConsistency(true)) {
            return false;
        }

        if (empty($data)) {
            return false;
        }

        if ( ! $resource) {
            $close = true;
            try {
                $resource = $this->file->fileOpen($this->path, 'a');
            } catch (Exception $exception) {
                $this->logger->debug('Failed to open file ' . $this->path);
                $this->logger->debug($exception->getMessage());
                $this->logger->debug($exception->getTraceAsString());
            }
        }

        foreach ($this->structure as $index => $item) {
            if ( ! isset($data[$index])) {
                $data[$index] = '';
            }
            $dataItemLength = strlen((string)$data[$index]);
            if ($item['length'] < $dataItemLength) {
                $this->logger->debug('length mismatch for item ' . $index + 1 . ' at code ' . $item['name']);
                return false;
            } else if ($item['length'] > $dataItemLength) {
                $gap = $item['length'] - $dataItemLength;
                for ($i = 0; $i < $gap; $i++) {
                    $data[$index] .= $item['fillWith'];
                }
            }
        }

        $dataLine = implode('', $data);
        $dataLine .= PHP_EOL;

        $this->logger->debug($dataLine);

        try {
            $dataLine = $this->file->fileWrite($resource, $dataLine);
            $this->logger->debug($dataLine);
        } catch (Exception $exception) {
            $this->logger->debug('Failed to write line in ' . $this->path);
            $this->logger->debug($exception->getMessage());
            $this->logger->debug($exception->getTraceAsString());
        }

        if (isset($close) && $close) {
            $this->file->fileClose($resource);
        }

        $this->logger->debug($data);

        return $data;
    }

    public function writeLines($data)
    {
        if ( ! $this->checkConsistency(true)) {
            return false;
        }

        try {
            $resource = $this->file->fileOpen($this->path, 'a');
        } catch (Exception $exception) {
            $this->logger->debug('Failed to open file ' . $this->path);
            $this->logger->debug($exception->getMessage());
            $this->logger->debug($exception->getTraceAsString());
        }

        $itemsWrited = 0;
        foreach ($data as $dataItem) {
            $this->writeLine($dataItem, $resource);
        }

        $this->file->fileClose($resource);

        return $itemsWrited;
    }
}