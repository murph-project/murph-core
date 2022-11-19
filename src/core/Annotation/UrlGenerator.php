<?php

namespace App\Core\Annotation;

/**
 * class UrlGenerator.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
#[\Attribute]
class UrlGenerator
{
    public string $service;

    public string $method;

    public array $options = [];
}
