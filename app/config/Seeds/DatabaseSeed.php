<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * DatabaseSeed — master seed that runs all individual seeds in order.
 *
 * Usage:
 *   bin/cake migrations seed --seed DatabaseSeed
 */
class DatabaseSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Calls individual seeds in dependency order.
     *
     * @return void
     */
    public function run(): void
    {
        // Disable FK checks
        $this->execute('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate child tables first
        $this->truncateTable('reviews');
        $this->truncateTable('order_items');
        $this->truncateTable('payments');
        $this->truncateTable('orders');
        $this->truncateTable('products');
        $this->truncateTable('categories');
        $this->truncateTable('users');

        // Re-enable FK checks
        $this->execute('SET FOREIGN_KEY_CHECKS=1;');

        // Seed in dependency order
        $this->call('UsersSeed');
        $this->call('CategoriesSeed');
        $this->call('ProductsSeed');
        $this->call('OrdersSeed');
        $this->call('PaymentsSeed');
        $this->call('OrderItemsSeed');
        $this->call('ReviewsSeed');
    }
}
