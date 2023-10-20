<?php

namespace App\Core\Event\Account;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * class PasswordRequestEvent.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class PasswordRequestEvent extends Event
{
    public const EVENT = 'account_event.password_request';

    public function __construct(protected User $user)
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
