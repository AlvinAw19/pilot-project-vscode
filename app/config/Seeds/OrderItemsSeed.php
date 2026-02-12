<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * OrderItems seed.
 *
 * Orders:
 *   1 (Charlie): 2x Wireless Earbuds(89.90) = 179.80
 *   2 (Charlie): 1x Clean Code(52.00) = 52.00
 *   3 (Diana):   1x Mechanical Keyboard(159.00) + 1x Power Bank(69.90) + 1x Resistance Bands(25.90) = 254.80
 *   4 (Diana):   1x Water Bottle(35.00) + 1x Pragmatic Programmer(48.50) = 83.50
 *   5 (Eve):     1x Yoga Mat(42.00) + 1x Wireless Earbuds(89.90) = 131.90
 *
 * Products: 1=Earbuds, 2=USBHub, 3=Keyboard, 4=PowerBank, 5=CleanCode,
 *           6=PragProg, 7=TShirt, 8=WaterBottle, 9=YogaMat, 10=ResistanceBands
 */
class OrderItemsSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * @return void
     */
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        // Base order items
        $baseOrderItems = [
            // Order 1 — Charlie: Wireless Earbuds x2
            [
                'order_id' => 1, 'product_id' => 1,
                'price' => 89.90, 'quantity' => 2, 'amount' => 179.80,
                'delivery_status' => 'delivered',
                'created' => $now, 'modified' => $now,
            ],
            // Order 2 — Charlie: Clean Code x1
            [
                'order_id' => 2, 'product_id' => 5,
                'price' => 52.00, 'quantity' => 1, 'amount' => 52.00,
                'delivery_status' => 'delivered',
                'created' => $now, 'modified' => $now,
            ],
            // Order 3 — Diana: Mechanical Keyboard x1
            [
                'order_id' => 3, 'product_id' => 3,
                'price' => 159.00, 'quantity' => 1, 'amount' => 159.00,
                'delivery_status' => 'delivered',
                'created' => $now, 'modified' => $now,
            ],
            // Order 3 — Diana: Power Bank x1
            [
                'order_id' => 3, 'product_id' => 4,
                'price' => 69.90, 'quantity' => 1, 'amount' => 69.90,
                'delivery_status' => 'delivering',
                'created' => $now, 'modified' => $now,
            ],
            // Order 3 — Diana: Resistance Bands x1
            [
                'order_id' => 3, 'product_id' => 10,
                'price' => 25.90, 'quantity' => 1, 'amount' => 25.90,
                'delivery_status' => 'pending',
                'created' => $now, 'modified' => $now,
            ],
            // Order 4 — Diana: Water Bottle x1
            [
                'order_id' => 4, 'product_id' => 8,
                'price' => 35.00, 'quantity' => 1, 'amount' => 35.00,
                'delivery_status' => 'delivered',
                'created' => $now, 'modified' => $now,
            ],
            // Order 4 — Diana: Pragmatic Programmer x1
            [
                'order_id' => 4, 'product_id' => 6,
                'price' => 48.50, 'quantity' => 1, 'amount' => 48.50,
                'delivery_status' => 'delivered',
                'created' => $now, 'modified' => $now,
            ],
            // Order 5 — Eve: Yoga Mat x1
            [
                'order_id' => 5, 'product_id' => 9,
                'price' => 42.00, 'quantity' => 1, 'amount' => 42.00,
                'delivery_status' => 'delivered',
                'created' => $now, 'modified' => $now,
            ],
            // Order 5 — Eve: Wireless Earbuds x1
            [
                'order_id' => 5, 'product_id' => 1,
                'price' => 89.90, 'quantity' => 1, 'amount' => 89.90,
                'delivery_status' => 'pending',
                'created' => $now, 'modified' => $now,
            ],
        ];

        // Generate 50 additional order items for more reviews
        $additionalOrderItems = [];
        $orders = [1, 2, 3, 4, 5]; // Existing orders
        $statuses = ['pending', 'delivering', 'delivered'];

        for ($i = 10; $i <= 59; $i++) { // IDs 10-59
            $orderId = $orders[array_rand($orders)];
            $productId = rand(1, 110); // Any product
            $quantity = rand(1, 5);
            $price = rand(10, 500) + (rand(0, 99) / 100); // Random price
            $amount = $price * $quantity;
            $status = $statuses[array_rand($statuses)];

            $additionalOrderItems[] = [
                'order_id' => $orderId,
                'product_id' => $productId,
                'price' => $price,
                'quantity' => $quantity,
                'amount' => $amount,
                'delivery_status' => $status,
                'created' => $now,
                'modified' => $now,
            ];
        }

        $data = array_merge($baseOrderItems, $additionalOrderItems);

        $table = $this->table('order_items');
        $table->insert($data)->save();
    }
}
