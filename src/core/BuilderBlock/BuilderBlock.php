<?php

namespace App\Core\BuilderBlock;

abstract class BuilderBlock
{
    protected string $name;
    protected string $label;
    protected ?string $class = 'col-12';
    protected ?string $category = null;
    protected array $settings = [];
    protected array $widgets = [];
    protected array $vars = [];
    protected string $template = '';
    protected bool $isContainer = false;
    protected ?string $icon = null;
    protected int $order = 1;

    abstract public function configure();

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setIsContainer(bool $isContainer): self
    {
        $this->isContainer = $isContainer;

        return $this;
    }

    public function getIsContainer(): bool
    {
        return $this->isContainer;
    }

    public function setWidgets(array $widgets): self
    {
        $this->widgets = $widgets;

        return $this;
    }

    public function addWidget(string $widget): self
    {
        if (!in_array($widget, $this->widgets)) {
            $this->widgets[] = $widget;
        }

        return $this;
    }

    public function getWidgets(): array
    {
        return $this->widgets;
    }

    public function setSettings(array $settings): self
    {
        $this->settings = $settings;

        return $this;
    }

    public function addSetting(
        string $name,
        string $type = 'input',
        ?string $label = null,
        array $attributes = [],
        array $extraOptions = [],
        $default = null
    ) {
        $this->settings[$name] = [
            'label' => $label,
            'type' => $type,
            'attr' => $attributes,
            'default' => $default,
        ];

        foreach ($extraOptions as $key => $value) {
            if (!in_array($key, array_keys($this->settings[$name]))) {
                $this->settings[$name][$key] = $value;
            }
        }

        return $this;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function setTemplate(string $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setClass(?string $class): self
    {
        $this->class = $class;

        return $this;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setOrder(int $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function buildVars(array $data, array $context)
    {
    }

    public function getVars(): array
    {
        return $this->vars;
    }

    public function toArray(): array
    {
        return [
            'label' => $this->getLabel(),
            'category' => $this->getCategory(),
            'isContainer' => $this->getIsContainer(),
            'widgets' => $this->getWidgets(),
            'settings' => $this->getSettings(),
            'class' => $this->getClass(),
            'icon' => $this->getIcon(),
        ];
    }
}
