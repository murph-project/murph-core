<?php

namespace App\Core\Twig\Extension;

use App\Core\Ab\AbContainer;
use App\Core\Ab\AbTestInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AbTestExtension extends AbstractExtension
{
    protected AbContainer $container;

    public function __construct(AbContainer $container)
    {
        $this->container = $container;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('ab_test_exists', [$this, 'exists']),
            new TwigFunction('ab_test', [$this, 'get']),
            new TwigFunction('ab_test_result', [$this, 'result']),
            new TwigFunction('ab_test_value', [$this, 'value']),
        ];
    }

    public function exists(string $name): bool
    {
        return $this->container->has($name);
    }

    public function get(string $name): AbTestInterface
    {
        return $this->container->get($name);
    }

    public function result(string $name): string
    {
        return $this->get($name)->getResult();
    }

    public function value(string $name)
    {
        return $this->get($name)->getResultValue();
    }
}
