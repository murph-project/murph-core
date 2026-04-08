<?php

namespace App\Core\Factory;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * class UserFactory.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class UserFactory implements FactoryInterface
{
    protected UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function create(?string $email = null, ?string $password = null): User
    {
        $entity = new User();

        if (null !== $email) {
            $entity->setEmail($email);
        }

        if (null !== $password) {
            $entity->setPassword($this->hasher->hashPassword($entity, $password));
        }

        return $entity;
    }
}
