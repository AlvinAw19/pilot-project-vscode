<?php
declare(strict_types=1);

namespace App\Policy;

use Authorization\IdentityInterface;
use App\Model\Entity\User;

/**
 * User Policy
 *
 * Defines authorization rules for User entities.
 */
class UserPolicy
{
    /**
     * Check if the identity can add a user.
     *
     * @param \Authorization\IdentityInterface $identity The user identity.
     * @param \App\Model\Entity\User|null $user The user entity.
     * @return bool
     */
    public function canAdd(IdentityInterface $identity, ?User $user): bool
    {
        return $this->isAdmin($identity);
    }

    /**
     * Check if the identity can edit a user.
     *
     * @param \Authorization\IdentityInterface $identity The user identity.
     * @param \App\Model\Entity\User $user The user entity.
     * @return bool
     */
    public function canEdit(IdentityInterface $identity, User $user): bool
    {
        return $this->isAdmin($identity);
    }

    /**
     * Check if the identity can delete a user.
     *
     * @param \Authorization\IdentityInterface $identity The user identity.
     * @param \App\Model\Entity\User $user The user entity.
     * @return bool
     */
    public function canDelete(IdentityInterface $identity, User $user): bool
    {
        return $this->isAdmin($identity);
    }

    /**
     * Check if the identity can index/list users.
     *
     * @param \Authorization\IdentityInterface $identity The user identity.
     * @return bool
     */
    public function canIndex(IdentityInterface $identity): bool
    {
        return $this->isAdmin($identity);
    }

    /**
     * Check if the identity can view a user.
     *
     * @param \Authorization\IdentityInterface $identity The user identity.
     * @param \App\Model\Entity\User $user The user entity.
     * @return bool
     */
    public function canView(IdentityInterface $identity, User $user): bool
    {
        return $this->isAdmin($identity);
    }

    /**
     * Check if the identity is an admin.
     *
     * @param \Authorization\IdentityInterface $identity The user identity.
     * @return bool
     */
    private function isAdmin(IdentityInterface $identity): bool
    {
        return $identity->get('role') === 'admin';
    }
}