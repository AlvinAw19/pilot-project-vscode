<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Order;
use App\Model\Entity\User;
use Authorization\IdentityInterface;

/**
 * Order policy
 */
class OrderPolicy
{
    /**
     * Check if $user can index products
     * Admin can view all, seller can view own (filtered in controller)
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\Order $order The order.
     * @return bool
     */
    public function canIndex(IdentityInterface $user, ?Order $order = null)
    {
        return $this->isAdmin($user) || $this->isBuyer($user);
    }

    /**
     * Check if $user can view Order
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\Order $order The order.
     * @return bool
     */
    public function canView(IdentityInterface $user, Order $order)
    {
        return $this->isAdmin($user) || $this->isBuyerOrder($user, $order);
    }

    /**
     * Check if $user can checkout
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\Order|null $order The order.
     * @return bool
     */
    public function canCheckout(IdentityInterface $user, ?Order $order = null)
    {
        return $this->isBuyer($user);
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

    /**
     * Check if the user is a buyer
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @return bool
     */
    private function isBuyer(IdentityInterface $user)
    {
        return $user->role === User::ROLE_BUYER;
    }

    /**
     * Check if the order belongs to the buyer
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\Order $order The order.
     * @return bool
     */
    private function isBuyerOrder(IdentityInterface $user, Order $order)
    {
        return $this->isBuyer($user) && ($order->buyer_id === $user->id);
    }
}
