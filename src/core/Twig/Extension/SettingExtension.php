<?php

namespace App\Core\Twig\Extension;

use App\Core\Setting\NavigationSettingManager;
use App\Core\Setting\SettingManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SettingExtension extends AbstractExtension
{
    public function __construct(
        protected SettingManager $settingManager,
        protected NavigationSettingManager $navigationSettingManager
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('setting', [$this, 'getSetting']),
            new TwigFunction('navigation_setting', [$this, 'getNavigationSetting']),
        ];
    }

    public function getSetting(string $code)
    {
        $entity = $this->settingManager->get($code);

        return $entity ? $entity->getValue() : null;
    }

    public function getNavigationSetting($navigation, string $code)
    {
        $entity = $this->navigationSettingManager->get($navigation, $code);

        return $entity ? $entity->getValue() : null;
    }
}
