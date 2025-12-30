<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\User;
use Authorization\IdentityInterface;

/**
 * Log policy
 *
 * Restrict log viewing and management to admin users only
 */
class LogPolicy
{
    /**
     * Check if user can access index (view logs)
     *
     * @param \Authorization\IdentityInterface|null $user The user.
     * @param mixed $resource The resource being accessed.
     * @return bool
     */
    public function canIndex(?IdentityInterface $user, $resource): bool
    {
        // Only admin users can view logs
        return $user !== null && $user->role === 'admin';
    }

    /**
     * Check if user can clear logs
     *
     * @param \Authorization\IdentityInterface|null $user The user.
     * @param mixed $resource The resource being accessed.
     * @return bool
     */
    public function canClear(?IdentityInterface $user, $resource): bool
    {
        // Only admin users can clear logs
        return $user !== null && $user->role === 'admin';
    }
}
