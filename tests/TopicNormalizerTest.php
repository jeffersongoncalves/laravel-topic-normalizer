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

it('keeps the first occurrence and preserves order across lists', function () {
    $result = TopicNormalizer::normalize(
        ['Beta', 'Alpha'],
        ['alpha', 'Gamma', 'beta'],
    );

    expect($result)->toBe(['beta', 'alpha', 'gamma']);
});

it('slugs unicode accents down to ascii', function () {
    expect(TopicNormalizer::normalize(['Café', 'Über', 'naïve']))
        ->toBe(['cafe', 'uber', 'naive']);
});

it('returns an empty list when max is zero', function () {
    Config::set('topic-normalizer.max', 0);

    expect(TopicNormalizer::normalize(['a', 'b', 'c']))->toBe([]);
});

it('returns an empty list when max is negative', function () {
    Config::set('topic-normalizer.max', -5);

    expect(TopicNormalizer::normalize(['a', 'b', 'c']))->toBe([]);
});

it('drops topics shorter than the configured min length', function () {
    Config::set('topic-normalizer.min_length', 3);

    expect(TopicNormalizer::normalize(['a', 'go', 'php', 'laravel']))
        ->toBe(['php', 'laravel']);
});

it('overrides max per call via a named argument', function () {
    Config::set('topic-normalizer.max', 20);

    expect(TopicNormalizer::normalize(['a', 'b', 'c', 'd'], max: 2))
        ->toBe(['a', 'b']);
});

it('overrides max length per call via a named argument', function () {
    expect(TopicNormalizer::normalize(['abcde', 'abcdef'], maxLength: 5))
        ->toBe(['abcde']);
});

it('overrides min length per call via a named argument', function () {
    expect(TopicNormalizer::normalize(['a', 'go', 'php'], minLength: 3))
        ->toBe(['php']);
});

it('accepts snake_case aliases for the per-call overrides', function () {
    expect(TopicNormalizer::normalize(['abcde', 'abcdef'], max_length: 5))
        ->toBe(['abcde']);
});

it('returns an empty list when max is overridden to zero per call', function () {
    expect(TopicNormalizer::normalize(['a', 'b'], max: 0))->toBe([]);
});
