<?php

namespace JeffersonGoncalves\TopicNormalizer\Tests;

use JeffersonGoncalves\TopicNormalizer\TopicNormalizerServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            TopicNormalizerServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $configPath = __DIR__.'/../config/topic-normalizer.php';

        if (file_exists($configPath)) {
            $app['config']->set('topic-normalizer', require $configPath);
        }
    }
}
