<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Payments seed.
 *
 * One payment per order.
 */
class PaymentsSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * @return void
     */
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $data = [
            ['order_id' => 1, 'payment_type' => 'Credit Card', 'created' => $now, 'modified' => $now],
            ['order_id' => 2, 'payment_type' => 'QR Payment', 'created' => $now, 'modified' => $now],
            ['order_id' => 3, 'payment_type' => 'Credit Card', 'created' => $now, 'modified' => $now],
            ['order_id' => 4, 'payment_type' => 'Cash on Delivery', 'created' => $now, 'modified' => $now],
            ['order_id' => 5, 'payment_type' => 'QR Payment', 'created' => $now, 'modified' => $now],
        ];

        $table = $this->table('payments');
        $table->insert($data)->save();
    }
}
