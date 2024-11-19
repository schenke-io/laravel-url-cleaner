<?php

namespace SchenkeIo\LaravelUrlCleaner\Data;

use SchenkeIo\LaravelUrlCleaner\Exceptions\FileIoException;

/**
 * this class operates relativ from a given directory
 */
class FileIo
{
    public static string $dataDir = __DIR__.'/../../data';

    /**
     * @throws FileIoException
     */
    public function getJson(string $filePath): array
    {
        return json_decode($this->get($filePath), true);
    }

    /**
     * @throws FileIoException
     */
    public function putJson(string $filePath, array $json): void
    {
        $this->put($filePath, json_encode($json, JSON_PRETTY_PRINT));
    }

    /**
     * the glob function is used relative to the base directory
     */
    public function glob(string $pattern): array
    {
        return array_map(function ($filePath) {
            return substr($filePath, strlen(realpath(self::$dataDir)) + 1);
        }, glob($this->dataPath($pattern)));
    }

    /**
     * @throws FileIoException
     */
    public function put(string $filePath, string $content): void
    {
        $fullFilePath = $this->dataPath($filePath);
        $dirPath = dirname($fullFilePath);
        if (! file_exists($dirPath)) {
            mkdir($dirPath, 0755, true);
        }
        if (file_exists($fullFilePath)) {
            if (! is_writable($fullFilePath)) {
                throw new FileIoException("File '$fullFilePath' is not writable");
            }
        }
        file_put_contents($fullFilePath, $content);
    }

    /**
     * @throws FileIoException
     */
    public function get(string $filePath): string
    {
        $fullPath = $this->dataPath($filePath);
        if (file_exists($fullPath)) {
            if (is_file($fullPath)) {
                return file_get_contents($this->dataPath($filePath));
            } else {
                throw new FileIoException("Invalid filename: $filePath");
            }
        } else {
            throw new FileIoException("The file '$filePath' does not exist");
        }

    }

    public function unlink(string $filePath): void
    {
        if (file_exists($this->dataPath($filePath))) {
            unlink($this->dataPath($filePath));
        }

    }

    private function dataPath(string $filePath = ''): string
    {
        return realpath(self::$dataDir).'/'.ltrim($filePath, '/');
    }
}
