<?php

namespace App\Core\BuilderBlock;

class BuilderBlockContainer
{
    protected array $widgets = [];

    public function addWidget(BuilderBlock $widget): self
    {
        $widget->configure();

        $this->widgets[$widget->getName()] = $widget;

        return $this;
    }

    public function removeWidget(string $name)
    {
        unset($this->widgets[$name]);

        return $this;
    }

    public function getWidgets(): array
    {
        usort($this->widgets, fn(BuilderBlock $a, BuilderBlock $b) => $a->getOrder() <=> $b->getOrder());

        return $this->widgets;
    }

    public function hasWidget(string $name)
    {
        return isset($this->widgets[$name]);
    }

    public function getWidget(string $name): BuilderBlock
    {
        return $this->widgets[$name];
    }
}
