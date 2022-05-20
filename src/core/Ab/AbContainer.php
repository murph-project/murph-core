<?php

namespace App\Core\Ab;

/**
 * class AbContainer.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class AbContainer
{
    protected array $tests = [];

    public function add(AbTest $test): self
    {
        $this->tests[$test->getName()] = $test;

        return $this;
    }

    public function has(string $name): bool
    {
        return isset($this->tests[$name]);
    }

    public function get(string $name): AbTest
    {
        return $this->tests[$name];
    }
}
