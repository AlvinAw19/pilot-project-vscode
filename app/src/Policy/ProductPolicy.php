<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Product;
use App\Model\Entity\User;
use Authorization\IdentityInterface;

/**
 * Product policy
 */
class ProductPolicy
{
    /**
     * Check if $user can add Product
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\Product $product The user's product.
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Product $product)
    {
        return $user->role === User::ROLE_SELLER;
    }

    /**
     * Check if $user can edit Product
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\Product $product The user's product.'
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Product $product)
    {
        return $product->seller_id === $user->id || $user->role === User::ROLE_ADMIN;
    }

    /**
     * Check if $user can delete Product
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\Product $product The user's product.'
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Product $product)
    {
        return $product->seller_id === $user->id || $user->role === User::ROLE_ADMIN;
    }

    /**
     * Check if $user can view Product
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\Product $product The user's product.'
     * @return bool
     */
    public function canView(IdentityInterface $user, Product $product)
    {
        return $product->seller_id === $user->id || $user->role === User::ROLE_ADMIN;
    }

    /**
     * Check if $user can index products
     * Admin can view all, seller can view own (filtered in controller)
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\Product $product The user's product.
     * @return bool
     */
    public function canIndex(IdentityInterface $user, ?Product $product = null)
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_SELLER]);
    }
}
