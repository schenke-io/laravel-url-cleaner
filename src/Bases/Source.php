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

/**
 * Enum representing different data sources for URL cleaning masks.
 *
 * Each case corresponds to a specific source of marketing or tracking parameters,
 * or top-level domains. It provides methods to handle source types,
 * converters, and file paths for these sources.
 */
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

    /**
     * Get the Source case from a cleaner class.
     *
     * @param  BaseCleaner  $class  The cleaner class instance.
     * @return self|null The matching Source case or null if not found.
     */
    public static function fromClass(BaseCleaner $class): ?self
    {
        return self::tryFrom(class_basename($class));
    }

    /**
     * Get the number of masks available for this source.
     *
     * @param  FileIo  $fileIo  The FileIo instance to use.
     * @return int The count of masks.
     *
     * @throws FileIoException
     */
    public function maskCount(FileIo $fileIo = new FileIo): int
    {
        return MaskTree::fromSource($this, $fileIo)->maskArray()->count();
    }

    /**
     * Determine if this source provides masks for all cases.
     *
     * @return bool True if it's a source for all, false otherwise.
     */
    public function isSourceForAll(): bool
    {
        return match ($this->name) {
            'Marketing00',
            'Marketing01',
            'Marketing02',
            'Marketing03',
            'Marketing04' => true,
            default => false,
        };
    }

    /**
     * Get the path to the final JSON file for this source.
     *
     * @return string The relative path to the JSON file.
     */
    public function pathFinalJson(): string
    {
        return match ($this) {
            default => 'final/'.strtolower($this->name).'.json',
        };
    }

    /**
     * Create a Transformer for this source.
     *
     * @param  FileIo  $fileIo  The FileIo instance.
     * @param  WebIo  $webIo  The WebIo instance.
     * @return Transformer The configured transformer.
     */
    public function transformer(FileIo $fileIo, WebIo $webIo): Transformer
    {
        return new Transformer($this->sourceType($fileIo, $webIo), $this->converter($fileIo));
    }

    /**
     * Get the appropriate converter for this source.
     *
     * @param  FileIo  $fileIo  The FileIo instance.
     * @return BaseConverter The configured converter.
     */
    public function converter(FileIo $fileIo): BaseConverter
    {
        return match ($this->name) {
            'Marketing00' => new TextLinesMasksConverter('@(.*)@', $fileIo),
            'Marketing01' => new TextLinesMasksConverter('@^(.*?),@', $fileIo),
            'Marketing02' => new TextLinesMasksConverter('@^(.*?)$@', $fileIo),
            'Marketing03' => new JsonMasksConverter('categories.*.params', $fileIo),
            'Marketing04' => new TextLineMasksConverter(',', $fileIo),
            'MarketingUnique',
            'MarketingNarrow',
            'MarketingBroad' => new AllMarketingMasksConverter($this, $fileIo),
            'TopLevelDomains' => new ArrayConverter('@^([-\w]+)$@', $fileIo, true, true),
        };
    }

    /**
     * Get the source type handler for this source.
     *
     * @param  FileIo  $fileIo  The FileIo instance.
     * @param  WebIo  $webIo  The WebIo instance.
     * @return BaseSourceType The configured source type handler.
     */
    public function sourceType(FileIo $fileIo, WebIo $webIo): BaseSourceType
    {
        return match ($this->name) {
            'Marketing00' => new DirectorySourceType($this, $fileIo),
            'Marketing01',
            'Marketing02',
            'Marketing03',
            'Marketing04' => new GithubSourceType($this, $webIo, $fileIo),
            'TopLevelDomains' => new WebSourceType($this, $webIo, $fileIo),
            'MarketingUnique',
            'MarketingNarrow',
            'MarketingBroad' => new NoSourceType($this, $fileIo),
        };
    }

    /**
     * Get the base identifier for the source (e.g., GitHub repo or local path).
     *
     * @return string|null The source base or null.
     */
    public function sourceBase(): ?string
    {
        return match ($this->name) {
            'Marketing00' => 'resources/manual',
            'Marketing01' => 'mpchadwick/tracking-query-params-registry',
            'Marketing02' => 'spekulatius/url-parameter-tracker-list',
            'Marketing03' => 'Smile4ever/Neat-URL',
            'Marketing04' => 'henkisdabro/platform-url-click-id-parameters',
            'TopLevelDomains' => 'resources/top-level-domains',
            default => null,
        };
    }

    /**
     * Get the file path or URL for the source data.
     *
     * @return string|null The source file path/URL or null.
     */
    public function sourceFile(): ?string
    {
        return match ($this->name) {
            'Marketing00' => 'resources/manual.txt',
            'Marketing01' => 'master/_data/params.csv',
            'Marketing02' => 'master/common-tracking-params.txt',
            'Marketing03' => 'master/data/default-params-by-category.json',
            'Marketing04' => 'main/parameter_list.csv',
            'TopLevelDomains' => 'https://data.iana.org/TLD/tlds-alpha-by-domain.txt',
            default => null,
        };
    }

    /**
     * Get the path where the source data is copied locally.
     *
     * @return string The local copy path.
     */
    public function pathSourceCopy(): string
    {
        $sourceFile = $this->sourceFile() ?? '';

        return $this->sourceBase().'.'.pathinfo($sourceFile, PATHINFO_EXTENSION);
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
