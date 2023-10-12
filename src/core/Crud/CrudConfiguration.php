<?php

namespace App\Core\Crud;

/**
 * class CrudConfiguration.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class CrudConfiguration
{
    protected array $pageTitles = [];
    protected array $pageRoutes = [];
    protected array $pageRouteParams = [];
    protected array $actions = [];
    protected array $batchActions = [];
    protected array $actionTitles = [];
    protected array $forms = [];
    protected array $formOptions = [];
    protected array $inlineForms = [];
    protected array $views = [];
    protected array $viewDatas = [];
    protected array $fields = [];
    protected array $maxPerPage = [];
    protected array $locales = [];
    protected array $defaultSort = [];
    protected array $isSortableCollection = [];
    protected string $sortableCollectionProperty = 'sortOrder';
    protected ?string $defaultLocale = null;
    protected bool $showActions = true;

    protected static $self;

    public static function create()
    {
        if (null === self::$self) {
            self::$self = new self();
        }

        return self::$self;
    }

    // --

    public function setPageTitle(string $page, string $title): self
    {
        $this->pageTitles[$page] = $title;

        return $this;
    }

    public function getPageTitle(string $page, ?string $default = null): ?string
    {
        return $this->pageTitles[$page] ?? $default;
    }

    // --

    public function setPageRoute(string $page, string $route): self
    {
        $this->pageRoutes[$page] = $route;

        return $this;
    }

    public function getPageRoute(string $page): ?string
    {
        return $this->pageRoutes[$page];
    }

    public function setPageRouteParams(string $page, array $params): self
    {
        $this->pageRouteParams[$page] = $params;

        return $this;
    }

    public function getPageRouteParams(string $page): array
    {
        return $this->pageRouteParams[$page] ?? [];
    }

    // --

    public function setForm(string $context, string $form): self
    {
        $this->forms[$context] = $form;

        return $this;
    }

    public function getForm(string $context): ?string
    {
        return $this->forms[$context] ?? null;
    }

    public function setFormOptions(string $context, array $options = []): self
    {
        $this->formOptions[$context] = $options;

        return $this;
    }

    public function getFormOptions(string $context): array
    {
        return $this->formOptions[$context] ?? [];
    }

    // --

    public function setAction(string $page, string $action, bool|callable $enabled): self
    {
        if (!isset($this->actions[$page])) {
            $this->actions[$page] = [];
        }

        $this->actions[$page][$action] = $enabled;

        return $this;
    }

    public function getAction(string $page, string $action, bool $default = true, array $callableParamaters = [])
    {
        if (!isset($this->actions[$page][$action])) {
            return $default;
        }

        if (is_bool($this->actions[$page][$action])) {
            return $this->actions[$page][$action];
        }

        return call_user_func_array(
            $this->actions[$page][$action],
            $callableParamaters
        );
    }

    public function setGlobalBatchAction(
        string $page,
        string $action,
        string $label,
        callable $callback
    ): self {
        $this->setBatchAction($page, $action, $label, $callback);
        $this->batchActions[$page][$action]['isGlobal'] = true;

        return $this;
    }

    public function setBatchAction(
        string $page,
        string $action,
        string $label,
        callable $callback
    ): self {
        if (!isset($this->batchActions[$page])) {
            $this->batchActions[$page] = [];
        }

        $this->batchActions[$page][$action] = [
            'label' => $label,
            'callback' => $callback,
            'isGlobal' => false,
        ];

        return $this;
    }

    public function getBatchActions(string $page)
    {
        return $this->batchActions[$page] ?? [];
    }

    public function getBatchAction(string $page, string $action)
    {
        return $this->batchActions[$page][$action] ?? null;
    }

    public function hasBatchAction(string $page)
    {
        return !empty($this->batchActions[$page]);
    }

    // --

    public function setActionTitle(string $page, string $action, string $title): self
    {
        if (!isset($this->actionTitles[$page])) {
            $this->actionTitles[$page] = [];
        }

        $this->actions[$page][$action] = $title;

        return $this;
    }

    public function getActionTitle(string $page, string $action, ?string $default = null): ?string
    {
        return $this->actionTitles[$page][$action] ?? $default;
    }

    // --

    public function setView(string $context, string $view): self
    {
        $this->views[$context] = $view;

        return $this;
    }

    public function getView(string $context, ?string $default = null)
    {
        if (null === $default) {
            $default = sprintf('@Core/admin/crud/%s.html.twig', $context);
        }

        return $this->views[$context] ?? $default;
    }

    public function addViewData(string $context, string $name, $value): self
    {
        if (!isset($this->viewDatas[$context])) {
            $this->viewDatas[$context] = [];
        }

        $this->viewDatas[$context][$name] = $value;

        return $this;
    }

    public function setViewDatas(string $context, array $datas): self
    {
        foreach ($datas as $name => $value) {
            $this->addViewData($name, $value);
        }

        return $this;
    }

    public function getViewDatas(string $context): array
    {
        return $this->viewDatas[$context] ?? [];
    }

    public function getViewData(string $context, string $name, $defaultValue = null)
    {
        return $this->viewDatas[$context][$name] ?? $defaultValue;
    }

    // --

    public function setField(string $context, string $label, string $field, array $options): self
    {
        if (!isset($this->fields[$context])) {
            $this->fields[$context] = [];
        }

        $this->fields[$context][$label] = [
            'field' => $field,
            'options' => $options,
        ];

        return $this;
    }

    public function getFields(string $context): array
    {
        return $this->fields[$context] ?? [];
    }

    public function setFields(string $context, array $fields): self
    {
        $this->fields[$context] = $fields;

        return $this;
    }

    // --

    public function setMaxPerPage(string $page, int $max): self
    {
        $this->maxPerPage[$page] = $max;

        return $this;
    }

    public function getMaxPerPage(string $page, int $default = 20)
    {
        return $this->maxPerPage[$page] ?? $default;
    }

    // --

    public function setDoubleClick(string $page, bool $enabled): self
    {
        $this->doubleClick[$page] = $enabled;

        return $this;
    }

    public function getDoubleClick(string $page): bool
    {
        return $this->doubleClick[$page] ?? false;
    }

    // --

    public function setI18n(array $locales, string $defaultLocale): self
    {
        $this->locales = $locales;
        $this->defaultLocale = $defaultLocale;

        return $this;
    }

    public function getLocales(): array
    {
        return $this->locales;
    }

    public function getDefaultLocale(): ?string
    {
        return $this->defaultLocale;
    }

    public function isI18n(): bool
    {
        return !empty($this->locales);
    }

    // --

    public function setDefaultSort(string $context, string $label, string $direction = 'asc'): self
    {
        $this->defaultSort[$context] = [
            'label' => $label,
            'direction' => $direction,
        ];

        return $this;
    }

    public function getDefaultSort(string $context)
    {
        return $this->defaultSort[$context] ?? null;
    }

    public function setIsSortableCollection(string $page, bool $isSortableCollection): self
    {
        $this->isSortableCollection[$page] = $isSortableCollection;

        return $this;
    }

    public function getIsSortableCollection(string $page): bool
    {
        return $this->isSortableCollection[$page] ?? false;
    }

    public function setSortableCollectionProperty(string $sortableCollectionProperty): self
    {
        $this->sortableCollectionProperty = $sortableCollectionProperty;

        return $this;
    }

    public function getSortableCollectionProperty(): string
    {
        return $this->sortableCollectionProperty;
    }

    public function setShowActions(bool $showActions): self
    {
        $this->showActions = $showActions;

        return $this;
    }

    public function getShowActions(): bool
    {
        return $this->showActions;
    }
}
