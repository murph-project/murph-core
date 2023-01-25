<?php

namespace App\Core\Ab;

/**
 * interface AbTestInterface.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
interface AbTestInterface
{
    public function getName(): string;

    public function getResult();

    public function setResult(string $result): self;

    public function isValidVariation($variation): bool;

    public function addVariation(string $name, $value, int $probability = null): self;

    public function getVariation($variation);

    public function getResultValue();

    public function setDuration(int $duration): self;

    public function getDuration(): int;

    public function run(): self;
}
