<?php

namespace App\Core\Twig\Extension;

use App\Core\String\StringBuilder;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class StringExtension extends AbstractExtension
{
    public function __construct(protected StringBuilder $stringBuilder)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new TwigFilter('build_string', [$this, 'buildString']),
        ];
    }

    public function buildString(?string $format, $object): string
    {
        return $this->stringBuilder->build((string) $format, $object);
    }
}
