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
     * Check if $user can view/edit their own profile
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The logged-in user.
     * @param \App\Model\Entity\User $profile The profile being accessed.
     * @return bool
     */
    public function canProfile(IdentityInterface $user, User $profile)
    {
        // Users can only access their own profile
        return $user->id === $profile->id;
    }

    /**
     * Check if $user can view admin logs
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @return bool
     */
    public function canViewLogs(IdentityInterface $user)
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
