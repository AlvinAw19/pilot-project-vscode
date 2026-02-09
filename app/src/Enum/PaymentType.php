<?php
declare(strict_types=1);

namespace App\Enum;

/**
 * Payment Type Enum
 */
class PaymentType
{
    public const QR_PAYMENT = 'QR Payment';
    public const CREDIT_CARD = 'Credit Card';
    public const CASH_ON_DELIVERY = 'Cash on Delivery';

    /**
     * Returns translatable payment type options for form inputs.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::QR_PAYMENT => __('QR Payment – Scan QR code to pay'),
            self::CREDIT_CARD => __('Credit Card – Pay with credit/debit card'),
            self::CASH_ON_DELIVERY => __('Cash on Delivery – Pay when you receive'),
        ];
    }
}
