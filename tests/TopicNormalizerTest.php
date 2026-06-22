<?php

use Illuminate\Support\Facades\Config;
use JeffersonGoncalves\TopicNormalizer\TopicNormalizer;

it('slugs, merges and deduplicates across lists', function () {
    $result = TopicNormalizer::normalize(
        ['Laravel', 'PHP Package'],
        ['laravel', 'filament'],
    );

    expect($result)->toBe(['laravel', 'php-package', 'filament']);
});

it('drops non-strings, empties and overly long values', function () {
    $result = TopicNormalizer::normalize([
        'ok',
        123,
        null,
        '   ',
        str_repeat('x', 60),
    ]);

    expect($result)->toBe(['ok']);
});

it('caps the result at the configured max', function () {
    Config::set('topic-normalizer.max', 3);

    $result = TopicNormalizer::normalize(['a', 'b', 'c', 'd', 'e']);

    expect($result)->toBe(['a', 'b', 'c']);
});

it('honours a custom max length', function () {
    Config::set('topic-normalizer.max_length', 5);

    expect(TopicNormalizer::normalize(['abcde', 'abcdef']))->toBe(['abcde']);
});

it('returns an empty list when given nothing', function () {
    expect(TopicNormalizer::normalize())->toBe([]);
    expect(TopicNormalizer::normalize([]))->toBe([]);
});
