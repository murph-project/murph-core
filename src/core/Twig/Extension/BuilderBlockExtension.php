<?php

namespace App\Core\Twig\Extension;

use App\Core\BuilderBlock\BuilderBlockContainer;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class BuilderBlockExtension extends AbstractExtension
{
    public function __construct(protected Environment $twig, protected BuilderBlockContainer $container)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new TwigFilter('block_to_html', [$this, 'buildHtml'], ['is_safe' => ['html']]),
        ];
    }

    public function buildHtml($data): string
    {
        if (null === $data) {
            return null;
        }

        if (isset($data['widget'])) {
            return $this->twig->render($this->container->getWidget($data['widget'])->getTemplate(), [
                'id' => $data['id'],
                'settings' => $data['settings'],
                'children' => $data['children'],
            ]);
        }

        $render = '';
        foreach ($data as $item) {
            $render .= $this->buildHtml($item);
        }

        return $render;
    }
}
