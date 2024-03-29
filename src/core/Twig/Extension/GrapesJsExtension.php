<?php

namespace App\Core\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

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
        $html = $this->extractData($value, 'html');

        $html = str_replace($this->getHtmlStringsToRemove(), '', $html);

        return preg_replace('#</?body[^>]*>#', '', $html);
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

    protected function getHtmlStringsToRemove(): array
    {
        return [
            'draggable="true"',
        ];
    }
}
