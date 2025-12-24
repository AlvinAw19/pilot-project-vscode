<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Category;
use App\Model\Entity\User;
use Authorization\IdentityInterface;

/**
 * Category policy
 */
class CategoryPolicy
{
    /**
     * Check if $user can index category
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\Category $category The category entity.
     * @return bool
     */
    public function canIndex(IdentityInterface $user, ?Category $category)
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if $user can add Category
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\Category $category The category entity.
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Category $category)
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if $user can edit Categories
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\Category $category The category entity.
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Category $category)
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if $user can delete Categories
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\Category $category The category entity.
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Category $category)
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if $user can view Categories
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\Category $category The category entity.
     * @return bool
     */
    public function canView(IdentityInterface $user, Category $category)
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
