<?php

namespace App\Core\BuilderBlock;

class BuilderBlockContainer
{
    protected array $widgets = [];

    public function addWidget(BuilderBlock $widget): void
    {
        $widget->configure();

        $this->widgets[$widget->getName()] = $widget;
    }

    public function getWidgets(): array
    {
        return $this->widgets;
    }

    public function getWidget(string $name): BuilderBlock
    {
        return $this->widgets[$name];
    }
}
