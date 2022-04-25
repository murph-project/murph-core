<?php

namespace App\Core\Slugify;

use Cocur\Slugify\Slugify as BaseSlugify;

/**
 * class CodeSlugify.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class CodeSlugify extends Slugify
{
    protected function create(): BaseSlugify
    {
        $slugify = new BaseSlugify([
            'separator' => '_',
            'lowercase' => true,
            'regexp' => '/[^A-Za-z0-9_]+/',
        ]);

        $slugify->activateRuleSet('french');

        return $slugify;
    }
}
