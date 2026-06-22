<?php

declare(strict_types=1);

namespace JeffersonGoncalves\TopicNormalizer;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TopicNormalizerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-topic-normalizer')
            ->hasConfigFile();
    }
}
