<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\CartItem;
use App\Model\Entity\User;
use Authorization\IdentityInterface;

/**
 * CartItem policy
 */
class CartItemPolicy
{
    /**
     * Check if $user can index cart items
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\CartItem|null $cartItem The cart item.
     * @return bool
     */
    public function canIndex(IdentityInterface $user, ?CartItem $cartItem = null)
    {
        return $this->isBuyer($user);
    }

    /**
     * Check if $user can add cart item
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\CartItem $cartItem The cart item.
     * @return bool
     */
    public function canAdd(IdentityInterface $user, CartItem $cartItem)
    {
        return $this->isBuyer($user);
    }

    /**
     * Check if $user can update cart item
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\CartItem $cartItem The cart item.
     * @return bool
     */
    public function canUpdate(IdentityInterface $user, CartItem $cartItem)
    {
        return $this->isBuyer($user) && $this->isOwnCartItem($user, $cartItem);
    }

    /**
     * Check if $user can delete cart item
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\CartItem $cartItem The cart item.
     * @return bool
     */
    public function canDelete(IdentityInterface $user, CartItem $cartItem)
    {
        return $this->isBuyer($user) && $this->isOwnCartItem($user, $cartItem);
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
     * Check if the cart item belongs to the user
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\CartItem $cartItem The cart item.
     * @return bool
     */
    private function isOwnCartItem(IdentityInterface $user, CartItem $cartItem)
    {
        return $cartItem->buyer_id === $user->id;
    }
}
