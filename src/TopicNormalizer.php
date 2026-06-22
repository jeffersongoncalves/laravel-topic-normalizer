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
     * @param  array<int, mixed>  ...$lists
     * @return list<string>
     */
    public static function normalize(array ...$lists): array
    {
        $max = (int) config('topic-normalizer.max', 20);
        $maxLength = (int) config('topic-normalizer.max_length', 50);

        $out = [];

        foreach ($lists as $list) {
            foreach ($list as $raw) {
                if (! is_string($raw)) {
                    continue;
                }

                $topic = Str::slug(trim($raw));

                // Drop empties and implausible values (junk, overly long).
                if ($topic === '' || strlen($topic) > $maxLength) {
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
}
