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

namespace Optimole\Laravel\Routing;

use Illuminate\Routing\UrlGenerator as LaravelUrlGenerator;
use Optimole\Sdk\Optimole;

class UrlGenerator extends LaravelUrlGenerator
{
    /**
     * {@inheritdoc}
     */
    public function asset($path, $secure = null)
    {
        $asset = parent::asset($path, $secure);

        if (!$this->isOptimoleSdkInitialized() || !config('optimole.override_asset_helper')) {
            return $asset;
        }

        $extension = pathinfo($asset, PATHINFO_EXTENSION);

        if (in_array($extension, ['css', 'js'])) {
            $asset = $this->optimoleAsset($asset);
        } elseif (in_array($extension, ['gif', 'jpeg', 'jpe', 'jpg', 'png', 'webp'])) {
            $asset = $this->optimoleImage($asset);
        }

        return $asset;
    }

    /**
     * Generate an Optimole asset URL.
     */
    public function optimoleAsset($url, $cacheBuster = ''): string
    {
        return $this->isOptimoleSdkInitialized() ? Optimole::asset($url, $cacheBuster)->getUrl() : $url;
    }

    /**
     * Generate an Optimole image URL.
     */
    public function optimoleImage($url, $cacheBuster = ''): string
    {
        return $this->isOptimoleSdkInitialized() ? Optimole::image($url, $cacheBuster)->getUrl() : $url;
    }

    /**
     * Check if the Optimole SDK is initialized.
     */
    private function isOptimoleSdkInitialized(): bool
    {
        return config('optimole.key') && Optimole::initialized();
    }
}
