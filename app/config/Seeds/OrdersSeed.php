<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Orders seed.
 *
 * Buyers: 4=Charlie, 5=Diana, 6=Eve
 */
class OrdersSeed extends AbstractSeed
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
            // Charlie's orders
            [
                'buyer_id' => 4,
                'total_amount' => 179.80, // 2x Wireless Earbuds(89.90)
                'created' => $now,
                'modified' => $now,
            ],
            [
                'buyer_id' => 4,
                'total_amount' => 52.00, // 1x Clean Code(52.00)
                'created' => $now,
                'modified' => $now,
            ],
            // Diana's orders
            [
                'buyer_id' => 5,
                'total_amount' => 254.80, // 1x Mechanical Keyboard(159.00) + 1x Power Bank(69.90) + 1x Resistance Bands(25.90)
                'created' => $now,
                'modified' => $now,
            ],
            [
                'buyer_id' => 5,
                'total_amount' => 83.50, // 1x Water Bottle(35.00) + 1x Pragmatic Programmer(48.50)
                'created' => $now,
                'modified' => $now,
            ],
            // Eve's orders
            [
                'buyer_id' => 6,
                'total_amount' => 131.90, // 1x Yoga Mat(42.00) + 1x Wireless Earbuds(89.90)
                'created' => $now,
                'modified' => $now,
            ],
        ];

        $table = $this->table('orders');
        $table->insert($data)->save();
    }
}
