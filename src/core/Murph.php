<?php

namespace App\Core;

if (!defined('MURPH_VERSION')) {
    define('MURPH_VERSION', 'v1.21.1');
}

/**
 * class Murph.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class Murph
{
    public static function version(): string
    {
        return MURPH_VERSION;
    }
}
