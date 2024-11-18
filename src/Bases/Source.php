<?php

namespace SchenkeIo\LaravelUrlCleaner\Bases;

use ArchTech\Enums\From;
use GuzzleHttp\Exception\GuzzleException;
use SchenkeIo\LaravelUrlCleaner\Data\FileIo;
use SchenkeIo\LaravelUrlCleaner\Data\MaskTree;
use SchenkeIo\LaravelUrlCleaner\Data\WebIo;
use SchenkeIo\LaravelUrlCleaner\Exceptions\FileIoException;
use SchenkeIo\LaravelUrlCleaner\Makers\Converters\AllMarketingMasksConverter;
use SchenkeIo\LaravelUrlCleaner\Makers\Converters\ArrayConverter;
use SchenkeIo\LaravelUrlCleaner\Makers\Converters\JsonMasksConverter;
use SchenkeIo\LaravelUrlCleaner\Makers\Converters\TextLineMasksConverter;
use SchenkeIo\LaravelUrlCleaner\Makers\Converters\TextLinesMasksConverter;
use SchenkeIo\LaravelUrlCleaner\Makers\SourceTypes\DirectorySourceType;
use SchenkeIo\LaravelUrlCleaner\Makers\SourceTypes\GithubSourceType;
use SchenkeIo\LaravelUrlCleaner\Makers\SourceTypes\NoSourceType;
use SchenkeIo\LaravelUrlCleaner\Makers\SourceTypes\WebSourceType;
use SchenkeIo\LaravelUrlCleaner\Makers\Transformer;

enum Source
{
    use From;

    case Marketing00;
    case Marketing01;
    case Marketing02;
    case Marketing03;
    case Marketing04;

    case MarketingUnique;
    case MarketingNarrow;
    case MarketingBroad;

    case TopLevelDomains;

    public static function fromClass(BaseCleaner $class): ?self
    {
        return self::tryFrom(class_basename($class));
    }

    /**
     * @throws FileIoException
     */
    public function maskCount(FileIo $fileIo = new FileIo): int
    {
        return MaskTree::fromSource($this, $fileIo)->maskArray()->count();
    }

    public function isSourceForAll(): bool
    {
        return match ($this) {
            Source::Marketing00,
            Source::Marketing01,
            Source::Marketing02,
            Source::Marketing03,
            Source::Marketing04 => true,
            default => false,
        };
    }

    public function pathFinalJson(): string
    {
        return match ($this) {
            default => 'final/'.strtolower($this->name).'.json',
        };
    }

    public function transformer(FileIo $fileIo, WebIo $webIo): Transformer
    {
        return new Transformer($this->sourceType($fileIo, $webIo), $this->converter($fileIo));
    }

    public function converter(FileIo $fileIo): BaseConverter
    {
        return match ($this) {
            self::Marketing00 => new TextLinesMasksConverter('@(.*)@', $fileIo),
            self::Marketing01 => new TextLinesMasksConverter('@^(.*?),@', $fileIo),
            self::Marketing02 => new TextLinesMasksConverter('@^(.*?)$@', $fileIo),
            self::Marketing03 => new JsonMasksConverter('categories.*.params', $fileIo),
            self::Marketing04 => new TextLineMasksConverter(',', $fileIo),
            self::MarketingUnique,
            self::MarketingNarrow,
            self::MarketingBroad => new AllMarketingMasksConverter($this, $fileIo),
            self::TopLevelDomains => new ArrayConverter('@^([-\w]+)$@', $fileIo, true, true),
        };

    }

    public function sourceType(FileIo $fileIo, WebIo $webIo): BaseSourceType
    {
        return match ($this) {
            self::Marketing00 => new DirectorySourceType($this, $fileIo),
            self::Marketing01,
            self::Marketing02,
            self::Marketing03,
            self::Marketing04 => new GithubSourceType($this, $webIo, $fileIo),
            self::TopLevelDomains => new WebSourceType($this, $webIo, $fileIo),
            self::MarketingUnique,
            self::MarketingNarrow,
            self::MarketingBroad => new NoSourceType($this, $fileIo),
        };
    }

    public function sourceBase(): ?string
    {
        return match ($this) {
            self::Marketing00 => 'resources/manual',
            self::Marketing01 => 'mpchadwick/tracking-query-params-registry',
            self::Marketing02 => 'spekulatius/url-parameter-tracker-list',
            self::Marketing03 => 'Smile4ever/Neat-URL',
            self::Marketing04 => 'henkisdabro/platform-url-click-id-parameters',
            self::TopLevelDomains => 'resources/top-level-domains',
            default => null,
        };
    }

    public function sourceFile(): ?string
    {
        return match ($this) {
            self::Marketing00 => 'resources/manual.txt',
            self::Marketing01 => 'master/_data/params.csv',
            self::Marketing02 => 'master/common-tracking-params.txt',
            self::Marketing03 => 'master/data/default-params-by-category.json',
            self::Marketing04 => 'main/parameter_list.csv',
            self::TopLevelDomains => 'https://data.iana.org/TLD/tlds-alpha-by-domain.txt',
            default => null,
        };
    }

    public function pathSourceCopy(): string
    {
        return $this->sourceBase().'.'.pathinfo($this->sourceFile(), PATHINFO_EXTENSION);
    }

    /**
     * @throws FileIoException
     * @throws GuzzleException
     */
    public function makeSource(FileIo $fileIo = new FileIo, WebIo $webIo = new WebIo): string
    {
        return $this->transformer($fileIo, $webIo)->handle();
    }
}
