<?php
declare(strict_types=1);

namespace App\Enum;

/**
 * Delivery Status Enum
 */
class DeliveryStatus
{
    public const PENDING = 'pending';
    public const DELIVERING = 'delivering';
    public const DELIVERED = 'delivered';
    public const CANCELED = 'canceled';

    /**
     * Returns translatable status list for form select inputs.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::PENDING => __('Pending'),
            self::DELIVERING => __('Delivering'),
            self::DELIVERED => __('Delivered'),
            self::CANCELED => __('Canceled'),
        ];
    }
}
