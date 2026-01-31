<?php

namespace SchenkeIo\LaravelUrlCleaner\Data;

use Illuminate\Support\Facades\File;
use SchenkeIo\LaravelUrlCleaner\Exceptions\FileIoException;

/**
 * Handles file input/output operations relative to a data directory.
 *
 * This class provides methods to read and write files, including JSON support,
 * with paths resolved relative to the project's data directory.
 */
class FileIo
{
    public static string $dataDir = __DIR__.'/../../data';

    public static function reset(): void
    {
        self::$dataDir = __DIR__.'/../../data';
    }

    /**
     * @return array<mixed>
     *
     * @throws FileIoException
     */
    public function getJson(string $filePath): array
    {
        return json_decode($this->get($filePath), true) ?? [];
    }

    /**
     * @param  array<mixed>  $json
     *
     * @throws FileIoException
     */
    public function putJson(string $filePath, array $json): void
    {
        $this->put($filePath, json_encode($json, JSON_PRETTY_PRINT) ?: '');
    }

    /**
     * the glob function is used relative to the base directory
     *
     * @return array<int, string>
     */
    public function glob(string $pattern): array
    {
        $baseDir = realpath(self::$dataDir) ?: '';
        $fullPattern = $this->dataPath($pattern);

        return array_map(function ($filePath) use ($baseDir) {
            return substr($filePath, strlen($baseDir) + 1);
        }, File::glob($fullPattern));
    }

    /**
     * @throws FileIoException
     */
    public function put(string $filePath, string $content): void
    {
        $fullFilePath = $this->dataPath($filePath);
        $dirPath = File::dirname($fullFilePath);

        if (! File::exists($dirPath)) {
            File::makeDirectory($dirPath, 0755, true);
        }
        if (File::exists($fullFilePath)) {
            if (! File::isWritable($fullFilePath)) {
                throw new FileIoException("File '$fullFilePath' is not writable");
            }
        }
        File::put($fullFilePath, $content);
    }

    public function get(string $filePath): string
    {
        $fullPath = $this->dataPath($filePath);

        if (File::exists($fullPath)) {
            if (File::isFile($fullPath)) {
                if (! File::isReadable($fullPath)) {
                    throw new FileIoException("Could not read file: $filePath");
                }

                return File::get($fullPath);
            } else {
                throw new FileIoException("Invalid filename: $filePath");
            }
        } else {
            throw new FileIoException("The file '$filePath' does not exist");
        }

    }

    public function unlink(string $filePath): void
    {
        $fullPath = $this->dataPath($filePath);

        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }

    }

    private function dataPath(string $filePath = ''): string
    {
        return realpath(self::$dataDir).'/'.ltrim($filePath, '/');
    }
}
