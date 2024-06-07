<?php

declare(strict_types=1);

/*
 * This file is part of Optimole Laravel Package.
 *
 * (c) Optimole Team <friends@optimole.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Optimole\Laravel\Tests\Integration\Routing;

use Illuminate\Routing\RouteCollection;
use Optimole\Laravel\Routing\UrlGenerator;
use Optimole\Laravel\Tests\TestCase;

class UrlGeneratorTest extends TestCase
{
    public static function provideAssetPaths()
    {
        return [
            ['/foo/bar.css'],
            ['/foo/bar.js'],
        ];
    }

    public static function provideImagePaths()
    {
        return [
            ['/foo/bar.gif'],
            ['/foo/bar.jpeg'],
            ['/foo/bar.jpe'],
            ['/foo/bar.jpg'],
            ['/foo/bar.png'],
            ['/foo/bar.webp'],
        ];
    }

    /**
     * @dataProvider provideAssetPaths
     */
    public function testAssetReturnsOptimoleAssetUrl($path)
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        $this->assertSame(
            sprintf('https://optimole_key.i.optimole.com/f:%s/http://localhost%s', $extension, $path),
            (new UrlGenerator(new RouteCollection(), $this->app->make('request')))->asset($path)
        );
    }

    /**
     * @dataProvider provideImagePaths
     */
    public function testAssetReturnsOptimoleImageUrl($path)
    {
        $this->assertSame(
            sprintf('https://optimole_key.i.optimole.com/http://localhost%s', $path),
            (new UrlGenerator(new RouteCollection(), $this->app->make('request')))->asset($path)
        );
    }

    /**
     * @dataProvider provideAssetPaths
     * @dataProvider provideImagePaths
     */
    public function testAssetReturnsUnmodifiedUrlWhenOptimoleSdkIsNotInitialized($path)
    {
        config(['optimole.key' => null]);

        $this->assertSame(
            sprintf('http://localhost%s', $path),
            (new UrlGenerator(new RouteCollection(), $this->app->make('request')))->asset($path)
        );
    }

    /**
     * @dataProvider provideAssetPaths
     * @dataProvider provideImagePaths
     */
    public function testAssetReturnsUnmodifiedUrlWhenOverrideIsDisabled($path)
    {
        config(['optimole.override_asset_helper' => false]);

        $this->assertSame(
            sprintf('http://localhost%s', $path),
            (new UrlGenerator(new RouteCollection(), $this->app->make('request')))->asset($path)
        );
    }

    public function testOptimoleAssetWhenOptimoleSdkIsInitialized()
    {
        $this->assertSame(
            'https://optimole_key.i.optimole.com/cb:cache_buster/f:css/http://localhost/foo/bar.css',
            (new UrlGenerator(new RouteCollection(), $this->app->make('request')))->optimoleAsset('http://localhost/foo/bar.css', 'cache_buster')
        );
    }

    public function testOptimoleAssetWhenOptimoleSdkIsNotInitialized()
    {
        config(['optimole.key' => null]);

        $this->assertSame(
            'http://localhost/foo/bar.css',
            (new UrlGenerator(new RouteCollection(), $this->app->make('request')))->optimoleAsset('http://localhost/foo/bar.css')
        );
    }

    public function testOptimoleImageWhenOptimoleSdkIsInitialized()
    {
        $this->assertSame(
            'https://optimole_key.i.optimole.com/cb:cache_buster/http://localhost/foo/bar.png',
            (new UrlGenerator(new RouteCollection(), $this->app->make('request')))->optimoleImage('http://localhost/foo/bar.png', 'cache_buster')
        );
    }

    public function testOptimoleImageWhenOptimoleSdkIsNotInitialized()
    {
        config(['optimole.key' => null]);

        $this->assertSame(
            'http://localhost/foo/bar.png',
            (new UrlGenerator(new RouteCollection(), $this->app->make('request')))->optimoleImage('http://localhost/foo/bar.png')
        );
    }
}
