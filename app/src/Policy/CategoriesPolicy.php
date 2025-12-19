<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Categories;
use App\Model\Entity\User;
use Authorization\IdentityInterface;

/**
 * Categories policy
 */
class CategoriesPolicy
{
    /**
     * Check if $user can add Categories
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Categories $categories
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Categories $categories)
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if $user can edit Categories
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Categories $categories
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Categories $categories)
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if $user can delete Categories
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Categories $categories
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Categories $categories)
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if $user can view Categories
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Categories $categories
     * @return bool
     */
    public function canView(IdentityInterface $user, Categories $categories)
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if $user can index Categories
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \Cake\ORM\Query $query The query.
     * @return bool
     */
    public function canIndex(IdentityInterface $user, $query)
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if the user is an admin
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @return bool
     */
    private function isAdmin(IdentityInterface $user)
    {
        return $user instanceof User && $user->role === User::ROLE_ADMIN;
    }
}
