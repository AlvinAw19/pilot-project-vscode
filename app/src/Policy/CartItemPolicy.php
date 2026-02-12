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
     * Check if $user can index CartItem
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\CartItem $cartItem The cart item entity.
     * @return bool
     */
    public function canIndex(IdentityInterface $user, ?CartItem $cartItem)
    {
        return $this->isBuyer($user);
    }

    /**
     * Check if $user can add CartItem
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\CartItem $cartItem The cart item entity.
     * @return bool
     */
    public function canAdd(IdentityInterface $user, CartItem $cartItem)
    {
        return $this->isBuyer($user);
    }

    /**
     * Check if $user can update CartItem
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\CartItem $cartItem The cart item entity.
     * @return bool
     */
    public function canUpdate(IdentityInterface $user, CartItem $cartItem)
    {
        return $this->isBuyerCartItem($user, $cartItem);
    }

    /**
     * Check if $user can delete CartItem
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\CartItem $cartItem The cart item entity.
     * @return bool
     */
    public function canDelete(IdentityInterface $user, CartItem $cartItem)
    {
        return $this->isBuyerCartItem($user, $cartItem);
    }

    /**
     * Check if the user is an buyer
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @return bool
     */
    private function isBuyer(IdentityInterface $user)
    {
        return $user->role === User::ROLE_BUYER;
    }

    /**
     * Check if the cart item belongs to the buyer
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\CartItem $cartItem The cart item entity.
     * @return bool
     */
    private function isBuyerCartItem(IdentityInterface $user, CartItem $cartItem)
    {
        return $this->isBuyer($user) && ($cartItem->buyer_id === $user->id);
    }
}
