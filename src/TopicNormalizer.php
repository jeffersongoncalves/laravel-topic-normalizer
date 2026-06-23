<?php

declare(strict_types=1);

namespace JeffersonGoncalves\TopicNormalizer;

use Illuminate\Support\Str;

/**
 * Merge raw topic/keyword lists (GitHub topics, composer.json + package.json
 * keywords, Packagist keywords, …) into one slugged, deduplicated,
 * length-filtered and capped list.
 */
class TopicNormalizer
{
    /**
     * Merge and normalize one or more topic lists.
     *
     * Per-call overrides may be passed as named arguments alongside the lists,
     * each falling back to its config value:
     *   TopicNormalizer::normalize($a, $b, max: 5, maxLength: 30, minLength: 2);
     * Accepted keys: `max` / `maxLength` (alias `max_length`) / `minLength`
     * (alias `min_length`).
     *
     * @param  array<int, mixed>|int  ...$lists
     * @return list<string>
     */
    public static function normalize(mixed ...$lists): array
    {
        /** @var array<array-key, mixed> $args */
        $args = $lists;

        $max = self::option($args, ['max'], (int) config('topic-normalizer.max', 20));
        $maxLength = self::option($args, ['maxLength', 'max_length'], (int) config('topic-normalizer.max_length', 50));
        $minLength = self::option($args, ['minLength', 'min_length'], (int) config('topic-normalizer.min_length', 0));

        // A non-positive cap means "no topics". Guarding here also fixes the
        // off-by-one where the count check ran AFTER insertion, so max=0 used
        // to leak the first item.
        if ($max <= 0) {
            return [];
        }

        $out = [];

        foreach ($args as $list) {
            if (! is_array($list)) {
                continue;
            }

            foreach ($list as $raw) {
                if (! is_string($raw)) {
                    continue;
                }

                $topic = Str::slug(trim($raw));

                // Drop empties and implausible values (junk, too short/long).
                if ($topic === '' || strlen($topic) > $maxLength) {
                    continue;
                }

                if ($minLength > 0 && strlen($topic) < $minLength) {
                    continue;
                }

                $out[$topic] = true;

                if (count($out) >= $max) {
                    break 2;
                }
            }
        }

        return array_keys($out);
    }

    /**
     * Pull an int override out of the variadic args (passed as a named
     * argument), removing it so it isn't treated as a topic list. Falls back
     * to the given config-derived default.
     *
     * @param  array<array-key, mixed>  $args
     * @param  list<string>  $keys
     */
    private static function option(array &$args, array $keys, int $default): int
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $args)) {
                $value = $args[$key];
                unset($args[$key]);

                return is_numeric($value) ? (int) $value : $default;
            }
        }

        return $default;
    }
}
