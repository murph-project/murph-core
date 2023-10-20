<?php

namespace App\Core\Event\Setting;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * class NavigationSettingEvent.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class NavigationSettingEvent extends Event
{
    public const INIT_EVENT = 'navigation_setting_event.init';
    public const FORM_INIT_EVENT = 'navigation_setting_event.form_init';

    public function __construct(protected $data = null)
    {
    }

    public function getData()
    {
        return $this->data;
    }

    public function setOption(string $key, $value): self
    {
        $this->data['options'][$key] = $value;

        return $this;
    }
}
