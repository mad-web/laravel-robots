<?php

namespace MadWeb\Robots\Test;

class RobotsTest extends TestCase
{
    public function testNewInstanceEmpty()
    {
        $this->assertEquals('', $this->getRobotsService()->generate());
    }

    public function testAddSitemap()
    {
        $robots = $this->getRobotsService();
        $sitemap = 'sitemap.xml';

        $this->assertStringNotContainsString($sitemap, $robots->generate());
        $robots->addSitemap($sitemap);
        $this->assertStringContainsString("Sitemap: $sitemap", $robots->generate());
    }

    public function testAddUserAgent()
    {
        $robots = $this->getRobotsService();
        $userAgent = 'Google';

        $this->assertStringNotContainsString("User-agent: $userAgent", $robots->generate());
        $robots->addUserAgent($userAgent);
        $this->assertStringContainsString("User-agent: $userAgent", $robots->generate());
    }

    public function testaddHost()
    {
        $robots = $this->getRobotsService();
        $host = 'www.google.com.au';

        $this->assertStringNotContainsString("Host: $host", $robots->generate());
        $robots->addHost($host);
        $this->assertStringContainsString("Host: $host", $robots->generate());
    }

    public function testaddDisallow()
    {
        $robots = $this->getRobotsService();
        $path = '/dir/';
        $paths = ['/dir-1/', '/dir-2/', '/dir-3/'];

        // Test a single path.
        $this->assertStringNotContainsString("Disallow: $path", $robots->generate());
        $robots->addDisallow($path);
        $this->assertStringContainsString("Disallow: $path", $robots->generate());

        // Test array of paths.
        foreach ($paths as $path_test) {
            $this->assertStringNotContainsString("Disallow: $path_test", $robots->generate());
        }

        // Add the array of paths
        $robots->addDisallow($paths);

        // Check the old path is still there
        $this->assertStringContainsString("Disallow: $path", $robots->generate());
        foreach ($paths as $path_test) {
            $this->assertStringContainsString("Disallow: $path_test", $robots->generate());
        }
    }

    public function testaddAllow()
    {
        $robots = $this->getRobotsService();
        $path = '/dir/';
        $paths = ['/dir-1/', '/dir-2/', '/dir-3/'];

        // Test a single path.
        $this->assertStringNotContainsString("Allow: $path", $robots->generate());
        $robots->addAllow($path);
        $this->assertStringContainsString("Allow: $path", $robots->generate());

        // Test array of paths.
        foreach ($paths as $path_test) {
            $this->assertStringNotContainsString("Allow: $path_test", $robots->generate());
        }

        // Add the array of paths
        $robots->addAllow($paths);

        // Check the old path is still there
        $this->assertStringContainsString("Allow: $path", $robots->generate());

        foreach ($paths as $path_test) {
            $this->assertStringContainsString("Allow: $path_test", $robots->generate());
        }
    }

    public function testaddComment()
    {
        $robots = $this->getRobotsService();
        $comment_1 = 'Test comment';
        $comment_2 = 'Another comment';
        $comment_3 = 'Final test comment';

        $this->assertStringNotContainsString("# $comment_1", $robots->generate());
        $this->assertStringNotContainsString("# $comment_2", $robots->generate());
        $this->assertStringNotContainsString("# $comment_3", $robots->generate());

        $robots->addComment($comment_1);
        $this->assertStringContainsString("# $comment_1", $robots->generate());

        $robots->addComment($comment_2);
        $this->assertStringContainsString("# $comment_1", $robots->generate());
        $this->assertStringContainsString("# $comment_2", $robots->generate());

        $robots->addComment($comment_3);
        $this->assertStringContainsString("# $comment_1", $robots->generate());
        $this->assertStringContainsString("# $comment_2", $robots->generate());
        $this->assertStringContainsString("# $comment_3", $robots->generate());
    }

    public function testaddSpacer()
    {
        $robots = $this->getRobotsService();

        $lines = count(preg_split('/'.PHP_EOL.'/', $robots->generate()));
        $this->assertEquals(1, $lines); // Starts off with at least one line.

        $robots->addSpacer();
        $robots->addSpacer();
        $lines = count(preg_split('/'.PHP_EOL.'/', $robots->generate()));

        $this->assertEquals(2, $lines);
    }

    public function testReset()
    {
        $robots = $this->getRobotsService();

        $this->assertEquals('', $robots->generate());

        $robots->addComment('First Comment');
        $robots->addHost('www.google.com');
        $robots->addSitemap('sitemap.xml');
        $this->assertNotEquals('', $robots->generate());

        $robots->reset();
        $this->assertEquals('', $robots->generate());
    }

    public function testShouldIndex()
    {
        $robots = $this->getRobotsService();

        $robots->setShouldIndexCallback(function () {
            return true;
        });

        $this->assertTrue($robots->shouldIndex());
    }

    public function testShouldNotIndex()
    {
        $robots = $this->getRobotsService();

        $robots->setShouldIndexCallback(function () {
            return false;
        });

        $this->assertTrue(! $robots->shouldIndex());
    }

    public function testMetaTag()
    {
        $robots = $this->getRobotsService();

        $robots->setShouldIndexCallback(function () {
            return true;
        });

        $this->assertEquals('<meta name="robots" content="index, follow">', $robots->metaTag());
    }
}
