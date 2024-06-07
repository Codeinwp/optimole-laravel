<?php

/**
 * Optimole Laravel configuration file.
 */
return [
    'key' => env('OPTIMOLE_KEY'),

    'base_domain' => env('OPTIMOLE_BASE_DOMAIN', 'i.optimole.com'),

    'cache_buster' => env('OPTIMOLE_CACHE_BUSTER', ''),

    'override_asset_helper' => env('OPTIMOLE_OVERRIDE_ASSET_HELPER', true),
];
