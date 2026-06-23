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

Pass any number of lists. Non-strings are skipped; values are `Str::slug()`-ed, empties and out-of-range slugs dropped, duplicates removed (first occurrence wins, order preserved), and the result capped.

### Per-call overrides

The config defaults can be overridden per call with named arguments (each falls back to its config value):

```php
$topics = TopicNormalizer::normalize(
    $repo['topics'] ?? [],
    $composer['keywords'] ?? [],
    max: 10,        // cap this call at 10
    maxLength: 30,  // drop slugs longer than 30 chars
    minLength: 2,   // drop single-character noise
);
```

`max_length` / `min_length` are accepted as snake_case aliases. A `max` of `0` (or less) returns an empty list.

## Configuration

```bash
php artisan vendor:publish --tag="topic-normalizer-config"
```

| Key | Default | Description |
| --- | --- | --- |
| `max` | `20` | Maximum number of topics returned (`0` or less returns nothing). |
| `max_length` | `50` | Slugs longer than this are dropped as junk. |
| `min_length` | `0` | Slugs shorter than this are dropped as junk (`0` disables the check). |

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
