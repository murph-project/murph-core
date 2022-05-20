<?php

namespace App\Core\Ab;

/**
 * class AbTest.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class AbTest implements AbTestInterface
{
    protected $results;
    protected string $name;
    protected array $variations = [];
    protected array $probabilities = [];
    protected int $duration = 3600 * 24;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function setResult(string $result): self
    {
        $this->result = $result;

        return $this;
    }

    public function isValidVariation($variation): bool
    {
        return array_key_exists($variation, $this->variations);
    }

    public function addVariation(string $name, $value, int $probability = null): self
    {
        $this->variations[$name] = $value;
        $this->probabilities[$name] = $probability;

        return $this;
    }

    public function getVariation($variation)
    {
        return $this->variations[$variation];
    }

    public function getResultValue()
    {
        return $this->getVariation($this->getResult());
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function run(): self
    {
        $this->result = $this->chance();

        return $this;
    }

    protected function chance(): string
    {
        $sum = 0;
        $empty = 0;

        foreach ($this->probabilities as $name => $value) {
            $sum += $value;

            if (empty($value)) {
                ++$empty;
            }
        }

        if ($sum > 100) {
            throw new \LogicException('Test Error: Total variation probabilities is bigger than 100%');
        }

        if ($sum < 100) {
            foreach ($this->probabilities as $name => $value) {
                if (empty($value)) {
                    $this->probabilities[$name] = (100 - $sum) / $empty;
                }
            }
        }

        krsort($this->probabilities);

        $number = mt_rand(0, (int) array_sum($this->probabilities) * 10);
        $starter = 0;
        $return = '';

        foreach ($this->probabilities as $key => $val) {
            $starter += $val * 10;

            if ($number <= $starter) {
                $return = $key;

                break;
            }
        }

        return $return;
    }
}
