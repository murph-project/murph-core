<?php

namespace App\Core\Ab;

/**
 * class AbContainer.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class AbContainer implements AbContainerInterface
{
    protected array $tests = [];

    public function add(AbTestInterface $test): self
    {
        $this->tests[$test->getName()] = $test;

        return $this;
    }

    public function has(string $name): bool
    {
        return isset($this->tests[$name]);
    }

    public function get(string $name): AbTestInterface
    {
        return $this->tests[$name];
    }
}
