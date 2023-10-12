<?php

namespace App\Core\Twig\Extension;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class EditorJsExtension extends AbstractExtension
{
    protected Environment $twig;
    protected array $views = [];
    protected array $defaultAllowedBlocks = [
        'paragraph',
        'header',
        'quote',
        'delimiter',
        'warning',
        'list',
        'nestedList',
        'checkList',
        'table',
        'code',
        'raw',
        'image',
        'link',
    ];

    public function __construct(Environment $twig, ParameterBagInterface $params)
    {
        $this->twig = $twig;
        $blocks = $params->get('core')['editor_js']['blocks'] ?? [];

        foreach ($blocks as $block => $view) {
            $this->views[$block] = $view;

            if (!in_array($block, $this->defaultAllowedBlocks)) {
                $this->defaultAllowedBlocks[] = $block;
            }
        }

        foreach ($this->defaultAllowedBlocks as $block) {
            if (!isset($this->views[$block])) {
                $this->views[$block] = sprintf('@Core/editorjs/%s.html.twig', $block);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new TwigFilter('editorjs_to_html', [$this, 'buildHtml']),
        ];
    }

    public function buildHtml(string|array|object $data, ?array $allowedBlocks = null): string
    {
        if (is_string($data)) {
            $data = json_decode($data);
        }

        if (is_object($data)) {
            $data = json_decode(json_encode($data), true);
        }

        if (null === $data) {
            return '';
        }

        if (null === $allowedBlocks) {
            $allowedBlocks = $this->defaultAllowedBlocks;
        }

        $blocks = $data['blocks'] ?? [];
        $renders = '';

        $blocks = array_filter($data['blocks'] ?? [], function ($block) use ($allowedBlocks) {
            return isset($block['type']) && in_array($block['type'], $allowedBlocks);
        });

        foreach ($blocks as $block) {
            $renders .= $this->twig->render($this->views[$block['type']], $block['data'] ?? []);
        }

        return $renders;
    }
}
