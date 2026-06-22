<div class="filament-hidden">

![Laravel Topic Normalizer](https://raw.githubusercontent.com/jeffersongoncalves/laravel-topic-normalizer/master/art/jeffersongoncalves-laravel-topic-normalizer.png)

</div>

# Laravel Topic Normalizer

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/laravel-topic-normalizer.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-topic-normalizer)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-topic-normalizer/run-tests.yml?branch=master&label=tests&style=flat-square)](https://github.com/jeffersongoncalves/laravel-topic-normalizer/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-topic-normalizer/fix-php-code-style-issues.yml?branch=master&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/laravel-topic-normalizer/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/laravel-topic-normalizer.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-topic-normalizer)

Merge raw topic / keyword lists from several sources — GitHub topics, `composer.json` + `package.json` keywords, Packagist keywords — into one clean list: slugged, deduplicated, length-filtered and capped.

## Installation

```bash
composer require jeffersongoncalves/laravel-topic-normalizer
```

## Usage

```php
use JeffersonGoncalves\TopicNormalizer\TopicNormalizer;

$topics = TopicNormalizer::normalize(
    $repo['topics'] ?? [],          // GitHub topics
    $composer['keywords'] ?? [],    // composer.json
    $packageJson['keywords'] ?? [], // package.json
);
// => ['laravel', 'filament', 'php-package', …]  (slugged, unique, max 20)
```

Pass any number of lists. Non-strings are skipped; values are `Str::slug()`-ed, empties and over-long slugs dropped, duplicates removed, and the result capped.

## Configuration

```bash
php artisan vendor:publish --tag="topic-normalizer-config"
```

| Key | Default | Description |
| --- | --- | --- |
| `max` | `20` | Maximum number of topics returned. |
| `max_length` | `50` | Slugs longer than this are dropped as junk. |

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
