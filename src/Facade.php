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

namespace Optimole\Laravel;

use Illuminate\Support\Facades\Facade as LaravelFacade;
use Optimole\Sdk\Offload\Manager;
use Optimole\Sdk\Optimole;
use Optimole\Sdk\Resource\Asset;
use Optimole\Sdk\Resource\Image;

/**
 * @method static Asset   asset(string $assetUrl, string $cacheBuster = '')
 * @method static Image   image(string $imageUrl, string $cacheBuster = '')
 * @method static Manager offload()
 */
class Facade extends LaravelFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Optimole::class;
    }
}
