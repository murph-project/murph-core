<?php

namespace App\Core\Twig\Extension;

use App\Core\Entity\Site\Navigation;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class NavigationExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('navigation_color_badge', [$this, 'navigationColorBadge'], ['is_safe' => ['html']]),
        ];
    }

    public function navigationColorBadge(Navigation $entity): ?string
    {
        if (!$entity->getColor()) {
            return null;
        }

        return sprintf(
            '<span class="badge badge-square" style="background: %s">&nbsp;</span>',
            $entity->getColor()
        );
    }
}
