<?php

use SchenkeIo\LaravelUrlCleaner\Bases\Source;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Data\MaskTree;
use SchenkeIo\LaravelUrlCleaner\Data\UrlData;

it('can construct from new', function () {
    $maskTree = new MaskTree('x');
    $this->assertInstanceOf(MaskTree::class, $maskTree);
    $this->assertEquals($maskTree->filePath, 'x');
});

it('can construct from source', function () {
    $fileIo = Mockery::mock(FileIo::class);
    $fileIo->shouldReceive('getJson')->once()->andReturn([]);
    $maskTree = MaskTree::fromSource(Source::Marketing00, $fileIo);
    $this->assertInstanceOf(MaskTree::class, $maskTree);
    $this->assertEquals($maskTree->filePath, Source::Marketing00->pathFinalJson());
});

it('can construct from masks', function () {
    $maskTree = MaskTree::fromMasks(Source::Marketing00, ['a', 'b', 'c']);
    $this->assertInstanceOf(MaskTree::class, $maskTree);
    $this->assertEquals($maskTree->filePath, Source::Marketing00->pathFinalJson());
    $this->assertEquals(3, $maskTree->maskArray()->count());
});

it('can delete tree', function () {
    $maskTree = MaskTree::fromMasks(Source::Marketing00, ['a', 'b', 'c']);
    $this->assertEquals(3, $maskTree->maskArray()->count());
    $maskTree->delete();
    $this->assertEquals(0, $maskTree->maskArray()->count());
});

it('can load tree', function () {
    $fileIo = Mockery::mock(FileIo::class);
    $fileIo->shouldReceive('getJson')->once()->andReturn(['a' => ['b', 'c']]);
    $maskTree = new MaskTree('', $fileIo);
    $this->assertEquals(0, $maskTree->maskArray()->count());
    $maskTree->load();
    $this->assertEquals(2, $maskTree->maskArray()->count());
});

it('can store tree', function () {
    $fileIo = Mockery::mock(FileIo::class);
    $fileIo->shouldReceive('putJson')->once();
    $maskTree = MaskTree::fromMasks(Source::Marketing00, ['a', 'b', 'c'], $fileIo);
    $this->assertEquals(3, $maskTree->maskArray()->count());
    $maskTree->store();
    $this->assertEquals(3, $maskTree->maskArray()->count());
});

it('can get keys to remove', function () {
    $urlData = new UrlData('https://test.de/?a=12&b=13');
    $maskTree = MaskTree::fromMasks(Source::Marketing00, ['a', 'b@test.de', 'c']);
    $keysToRemove = $maskTree->getKeysToRemove($urlData);
    $this->assertEquals(['a', 'b'], $keysToRemove);
});

it('correctly handles domains in maskArray', function () {
    $maskTree = MaskTree::fromMasks(Source::Marketing00, ['a@domain.com', 'b']);
    $maskArray = $maskTree->maskArray();
    expect($maskArray->masks())->toContain('a@domain.com', 'b');
});
