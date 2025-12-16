<?php

declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Product;
use Authorization\IdentityInterface;

/**
 * Product policy
 */
class ProductPolicy
{
    /**
     * Check if $user can index products
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Product $product
     * @return bool
     */
    public function canIndex(IdentityInterface $user, Product $product)
    {
        return $user->role === 'seller' && $product->seller_id === $user->id;
    }

    /**
     * Check if $user can view product
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Product $product
     * @return bool
     */
    public function canView(IdentityInterface $user, Product $product)
    {
        return $user->role === 'seller' && $product->seller_id === $user->id;
    }

    /**
     * Check if $user can add product
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Product $product
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Product $product)
    {
        return $user->role === 'seller';
    }

    /**
     * Check if $user can edit product
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Product $product
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Product $product)
    {
        return $user->role === 'seller' && $product->seller_id === $user->id;
    }

    /**
     * Check if $user can delete product
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Product $product
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Product $product)
    {
        return $user->role === 'seller' && $product->seller_id === $user->id;
    }
}