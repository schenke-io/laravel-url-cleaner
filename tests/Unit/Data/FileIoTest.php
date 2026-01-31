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
    $readOnlyFile = 'readonly.txt';

    File::shouldReceive('dirname')->andReturn('some_dir');
    File::shouldReceive('exists')->withArgs(fn ($path) => str_contains($path, 'some_dir'))->andReturn(true);
    File::shouldReceive('exists')->withArgs(fn ($path) => str_contains($path, $readOnlyFile))->andReturn(true);
    File::shouldReceive('isWritable')->andReturn(false);

    $this->expectException(FileIoException::class);
    $this->expectExceptionMessage('is not writable');

    $fileIo = new FileIo;
    $fileIo->put($readOnlyFile, 'yes');
});

it('raise error when it cannot read a file', function () {
    $fileName = 'non_readable.txt';

    File::shouldReceive('exists')->withArgs(fn ($path) => str_contains($path, $fileName))->andReturn(true);
    File::shouldReceive('isFile')->andReturn(true);
    File::shouldReceive('isReadable')->andReturn(false);

    $this->expectException(FileIoException::class);
    $this->expectExceptionMessage("Could not read file: $fileName");

    $fileIo = new FileIo;
    $fileIo->get($fileName);
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
