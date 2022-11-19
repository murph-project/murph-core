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

    public function __construct(string $service, string $method, array $options = [])
    {
        $this->service = $service;
        $this->method = $method;
        $this->options = $options;
    }
}
