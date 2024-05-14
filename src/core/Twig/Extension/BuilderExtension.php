<?php

namespace App\Core\Twig\Extension;

use App\Core\BuilderBlock\BuilderBlockContainer;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class BuilderExtension extends AbstractExtension
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
            if (!$this->container->hasWidget($data['widget'])) {
                return '';
            }

            $widget = $this->container->getWidget($data['widget']);
            $widget->buildVars($data);

            return $this->twig->render($widget->getTemplate(), [
                'id' => $data['id'],
                'settings' => $data['settings'],
                'children' => $data['children'],
                'vars' => $widget->getVars(),
            ]);
        }

        $render = '';
        foreach ($data as $item) {
            $render .= $this->buildHtml($item);
        }

        return $render;
    }
}
