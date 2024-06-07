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

if (!function_exists('optimole_asset')) {
    function optimole_asset($url, $cacheBuster = ''): string
    {
        return app('url')->optimoleAsset($url, $cacheBuster);
    }
}

if (!function_exists('optimole_image')) {
    function optimole_image($url, $cacheBuster = ''): string
    {
        return app('url')->optimoleImage($url, $cacheBuster);
    }
}
