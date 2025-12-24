<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\User;
use Authorization\IdentityInterface;

/**
 * User policy
 */
class UserPolicy
{
    /**
     * Check if $user can index category
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @return bool
     */
    public function canIndex(IdentityInterface $user)
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if $user can add User
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @return bool
     */
    public function canAdd(IdentityInterface $user)
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if $user can edit User
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @return bool
     */
    public function canEdit(IdentityInterface $user)
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if $user can delete User
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @return bool
     */
    public function canDelete(IdentityInterface $user)
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if $user can view User
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @return bool
     */
    public function canView(IdentityInterface $user)
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if the user is an admin
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @return bool
     */
    private function isAdmin(IdentityInterface $user)
    {
        return $user->role === User::ROLE_ADMIN;
    }
}
