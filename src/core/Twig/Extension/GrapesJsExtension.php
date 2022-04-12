<?php

namespace App\Core\Twig\Extension;

use App\Core\String\StringBuilder;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\Environment;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GrapesJsExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new TwigFilter('grapesjs_html', [$this, 'getHtml']),
            new TwigFilter('grapesjs_css', [$this, 'getCss']),
        ];
    }

    public function getHtml(?string $value): ?string
    {
        return $this->extractData($value, 'html');
    }

    public function getCss(?string $value): ?string
    {
        return $this->extractData($value, 'css');
    }

    protected function extractData(?string $value, string $key): ?string
    {
        $data = json_decode($value, true);

        return $data[$key] ?? '';
    }
}
