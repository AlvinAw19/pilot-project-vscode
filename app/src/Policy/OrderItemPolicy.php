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
        return $this->isSeller($user);
    }

    /**
     * Check if $user can update status of order item
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\OrderItem $orderItem The order item.
     * @return bool
     */
    public function canUpdateStatus(IdentityInterface $user, OrderItem $orderItem)
    {
        return $this->isSellerOrderItem($user, $orderItem);
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
     * Check if the order item belongs to the seller's product
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\OrderItem $orderItem The order item.
     * @return bool
     */
    private function isSellerOrderItem(IdentityInterface $user, OrderItem $orderItem)
    {
        return $this->isSeller($user) && ($orderItem->product->seller_id === $user->id);
    }
}
