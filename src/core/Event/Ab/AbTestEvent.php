<?php

namespace App\Core\Event\Ab;

use App\Core\Ab\AbTest;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * class AbTestEvent.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class AbTestEvent extends Event
{
    public const INIT_EVENT = 'ab_test.init';
    public const RUN_EVENT = 'ab_test.run';

    public function __construct(protected AbTest $test)
    {
    }

    public function getTest(): AbTest
    {
        return $this->test;
    }
}
