<?php
declare(strict_types=1);

namespace App\Policy;

use Authorization\IdentityInterface;
use App\Model\Entity\Category;

/**
 * Category Policy
 *
 * Defines authorization rules for Category entities.
 */
class CategoryPolicy
{
    /**
     * Check if the identity can add a category.
     *
     * @param \Authorization\IdentityInterface $identity The user identity.
     * @param \App\Model\Entity\Category|null $category The category entity.
     * @return bool
     */
    public function canAdd(IdentityInterface $identity, ?Category $category): bool
    {
        return $this->isAdmin($identity);
    }

    /**
     * Check if the identity can edit a category.
     *
     * @param \Authorization\IdentityInterface $identity The user identity.
     * @param \App\Model\Entity\Category $category The category entity.
     * @return bool
     */
    public function canEdit(IdentityInterface $identity, Category $category): bool
    {
        return $this->isAdmin($identity);
    }

    /**
     * Check if the identity can delete a category.
     *
     * @param \Authorization\IdentityInterface $identity The user identity.
     * @param \App\Model\Entity\Category $category The category entity.
     * @return bool
     */
    public function canDelete(IdentityInterface $identity, Category $category): bool
    {
        return $this->isAdmin($identity);
    }

    /**
     * Check if the identity can index/list categories.
     *
     * @param \Authorization\IdentityInterface $identity The user identity.
     * @return bool
     */
    public function canIndex(IdentityInterface $identity): bool
    {
        return $this->isAdmin($identity);
    }

    /**
     * Check if the identity can view a category.
     *
     * @param \Authorization\IdentityInterface $identity The user identity.
     * @param \App\Model\Entity\Category $category The category entity.
     * @return bool
     */
    public function canView(IdentityInterface $identity, Category $category): bool
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