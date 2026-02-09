<?php
declare(strict_types=1);

namespace App\Mailer;

use Cake\Mailer\Mailer;
use Cake\Routing\Router;

/**
 * User Mailer
 */
class UserMailer extends Mailer
{
    /**
     * Send password reset email
     *
     * @param string $email User's email
     * @param string $token Reset token
     * @return void
     */
    public function passwordResetEmail(string $email, string $token): void
    {
        $resetUrl = Router::url([
            'controller' => 'Users',
            'action' => 'resetPassword',
            $token,
        ], true);

        $this
            ->setTo($email)
            ->setSubject('Password Reset Request')
            ->setEmailFormat('both')
            ->viewBuilder()
            ->setTemplate('password_reset')
            ->setLayout('default')
            ->setVar('resetUrl', $resetUrl)
            ->setVar('email', $email);

        $this->deliver();
    }
}
