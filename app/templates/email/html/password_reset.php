<?php
/**
 * Password Reset Email Template (HTML)
 *
 * Displays the password reset email content with a secure reset link.
 *
 * @var string $resetUrl The URL for password reset.
 * @var string $email The user's email address.
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
</head>
<body>
    <h1>Password Reset Request</h1>
    <p>Hello,</p>
    <p>You have requested to reset your password. Click the link below to reset it:</p>
    <p><a href="<?= $resetUrl ?>">Reset Password</a></p>
    <p>If you did not request this, please ignore this email.</p>
    <p>This link will expire in 1 hour.</p>
    <p>Best regards,<br>Your App Team</p>
</body>
</html>