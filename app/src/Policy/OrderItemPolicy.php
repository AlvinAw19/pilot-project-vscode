<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\OrderItem;
use App\Model\Entity\User;
use Authorization\IdentityInterface;

/**
 * OrderItem policy
 */
class OrderItemPolicy
{
    /**
     * Check if $user can index order items
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\OrderItem|null $orderItem The order item.
     * @return bool
     */
    public function canIndex(IdentityInterface $user, ?OrderItem $orderItem = null)
    {
        return $this->isSeller($user) || $this->isAdmin($user);
    }

    /**
     * Check if $user can update order item delivery status
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\OrderItem $orderItem The order item.
     * @return bool
     */
    public function canUpdateStatus(IdentityInterface $user, OrderItem $orderItem)
    {
        // Admin can update any order item
        if ($this->isAdmin($user)) {
            return true;
        }

        // Seller can only update order items for their own products
        if ($this->isSeller($user) && isset($orderItem->product)) {
            return $orderItem->product->seller_id === $user->id;
        }

        return false;
    }

    /**
     * Check if the user is a seller
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @return bool
     */
    private function isSeller(IdentityInterface $user)
    {
        return $user->role === User::ROLE_SELLER;
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
