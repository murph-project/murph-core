<?php

namespace App\Core\Ab;

/**
 * interface AbContainerInterface.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
interface AbContainerInterface
{
    public function add(AbTestInterface $test): self;

    public function has(string $name): bool;

    public function get(string $name): AbTestInterface;
}
