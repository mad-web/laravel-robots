<?php

namespace MadWeb\Robots\Test;

use Illuminate\Container\Container;
use MadWeb\Robots\RobotsFacade;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use PHPUnit\Runner\Version;

class TestCase extends OrchestraTestCase
{
    /** @return \MadWeb\Robots\Robots */
    protected function getRobotsService()
    {
        return Container::getInstance()->make('robots');
    }

    protected function getPackageProviders($app)
    {
        return [\MadWeb\Robots\RobotsServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Robots' => RobotsFacade::class,
        ];
    }

    /**
     * Added for support backward capability with PHPUnit < 8.0.
     */
    public static function assertStringContainsString(string $needle, string $haystack, string $message = ''): void
    {
        if (version_compare(Version::series(), '8.0') >= 0) {
            parent::assertStringContainsString($needle, $haystack, $message = '');
        } else {
            parent::assertContains($needle, $haystack, $message);
        }
    }

    /**
     * Added for support backward capability with PHPUnit < 8.0.
     */
    public static function assertStringNotContainsString(string $needle, string $haystack, string $message = ''): void
    {
        if (version_compare(Version::series(), '8.0') >= 0) {
            parent::assertStringNotContainsString($needle, $haystack, $message = '');
        } else {
            parent::assertNotContains($needle, $haystack, $message);
        }
    }
}
