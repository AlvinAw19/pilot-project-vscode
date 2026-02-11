<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Review;
use App\Model\Entity\User;
use Authorization\IdentityInterface;

/**
 * Review policy
 */
class ReviewPolicy
{
    /**
     * Check if $user can add Review
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Review $review The user's review.
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Review $review)
    {
        return $this->isBuyerOrderItem($user, $review);
    }

    /**
     * Check if $user can edit Review
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Review $review The user's review.'
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Review $review)
    {
        return $this->isBuyerOrderItem($user, $review);
    }

    /**
     * Check if the user is a buyer
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\Review $review The review.
     * @return bool
     */
    private function isBuyerOrderItem(IdentityInterface $user, Review $review)
    {
        if ($user->role !== User::ROLE_BUYER) {
            return false;
        }

        // For edit: check review ownership
        if ($review->user_id && $user->id !== $review->user_id) {
            return false;
        }

        // Check order item belongs to this buyer
        if ($review->order_item && $review->order_item->order) {
            return $review->order_item->order->buyer_id === $user->id;
        }

        return true;
    }
}
