<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Log;
use App\Model\Entity\User;
use Authorization\IdentityInterface;

/**
 * Log policy
 */
class LogPolicy
{
    /**
     * Check if $user can index category
     *
     * @param \Authorization\IdentityInterface&\App\Model\Entity\User $user The user.
     * @param \App\Model\Entity\Log $log The log entity.
     * @return bool
     */
    public function canIndex(IdentityInterface $user, ?Log $log)
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
