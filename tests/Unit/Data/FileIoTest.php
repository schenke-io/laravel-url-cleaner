<?php

use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Exceptions\FileIoException;

/*
 * test in this data directory only
 */
FileIo::$dataDir = __DIR__.'/../../data';
$subDir = FileIo::$dataDir.'/tmp';
$filePath = '/tmp/test.json';
@unlink(FileIo::$dataDir.$filePath);
@rmdir($subDir);

$readOnlyFile = '/readonly.txt';
@touch(FileIo::$dataDir.$readOnlyFile);
@chmod(FileIo::$dataDir.$readOnlyFile, 0444);

it('can handle basic file operations', function () use ($filePath, $subDir) {
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
    rmdir($subDir);
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

it('raise error when it cannot write a file', function () use ($readOnlyFile) {
    $this->expectException(FileIoException::class);
    $fileIo = new FileIo;
    $fileIo->put($readOnlyFile, 'yes');
});
