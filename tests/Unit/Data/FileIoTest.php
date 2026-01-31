<?php

use Illuminate\Support\Facades\File;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Exceptions\FileIoException;

/*
 * test in this data directory only
 */
beforeEach(function () {
    FileIo::$dataDir = __DIR__.'/../../data';
});

it('can handle basic file operations', function () {
    $subDir = FileIo::$dataDir.'/tmp';
    $filePath = '/tmp/test.json';
    File::delete(FileIo::$dataDir.$filePath);
    if (File::isDirectory($subDir)) {
        File::deleteDirectory($subDir);
    }

    $fileIo = new FileIo;
    $content = '[1,2]';
    $array = [1, 2];
    // put make dir and write file
    $this->assertFileDoesNotExist($subDir);
    $this->assertFileDoesNotExist(FileIo::$dataDir.$filePath);
    $fileIo->put($filePath, $content);
    $this->assertFileExists($subDir);
    $this->assertFileExists(FileIo::$dataDir.$filePath);
    // content is ok
    $this->assertEquals($content, $fileIo->get($filePath));
    // remove file
    $fileIo->unlink($filePath);
    $this->assertFileDoesNotExist(FileIo::$dataDir.$filePath);
    // write again as json
    $fileIo->putJson($filePath, $array);
    $this->assertFileExists(FileIo::$dataDir.$filePath);
    $this->assertEquals($array, $fileIo->getJson($filePath));
    // remove the file
    $fileIo->unlink($filePath);
    $this->assertFileDoesNotExist(FileIo::$dataDir.$filePath);
    // remove subdirectory
    File::deleteDirectory($subDir);
    $this->assertFileDoesNotExist($subDir);
});

it('raise exception when reading from a directory', function () {
    $this->expectException(FileIoException::class);
    $fileIo = new FileIo;
    $fileIo->get('test/');
});

it('raise exception when reading from non existing file', function () {
    $this->expectException(FileIoException::class);
    $fileIo = new FileIo;
    $fileIo->get('test file does not exist');
});

it('can use glob() relative to base dir', function () {
    $fileIo = new FileIo;
    $files = $fileIo->glob('/resources/manual/*.txt');
    expect($files)->toBe([
        'resources/manual/a.txt',
        'resources/manual/b.txt',
    ]);
});

it('raise error when it cannot write a file', function () {
    $readOnlyFile = '/readonly.txt';
    $fullPath = FileIo::$dataDir.$readOnlyFile;
    if (File::exists($fullPath)) {
        File::chmod($fullPath, 0644);
    }
    File::put($fullPath, '');
    File::chmod($fullPath, 0444);

    try {
        $this->expectException(FileIoException::class);
        $fileIo = new FileIo;
        $fileIo->put($readOnlyFile, 'yes');
    } finally {
        File::chmod($fullPath, 0644);
        File::delete($fullPath);
    }
});

it('raise error when it cannot read a file', function () {
    $fileIo = new FileIo;
    $fileName = 'non_readable.txt';
    $fileIo->put($fileName, 'content');
    $fullPath = FileIo::$dataDir.'/'.$fileName;
    File::chmod($fullPath, 0222); // make it non-readable

    try {
        $fileIo->get($fileName);
        $this->fail('Should have thrown an exception');
    } catch (FileIoException $e) {
        $this->assertStringContainsString('Could not read file', $e->getMessage());
    } finally {
        File::chmod($fullPath, 0644);
        $fileIo->unlink($fileName);
    }
});

it('can put content into an existing file', function () {
    $fileIo = new FileIo;
    $fileIo->put('test.txt', 'first');
    $fileIo->put('test.txt', 'second');
    $this->assertEquals('second', $fileIo->get('test.txt'));
    $fileIo->unlink('test.txt');
});

test('it can reset dataDir', function () {
    FileIo::$dataDir = '/tmp';
    FileIo::reset();
    expect(FileIo::$dataDir)->not->toBe('/tmp');
});
