<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Categories seed.
 */
class CategoriesSeed extends AbstractSeed
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
            ['name' => 'Electronics', 'slug' => 'electronics', 'created' => $now, 'modified' => $now],
            ['name' => 'Computers', 'slug' => 'computers', 'created' => $now, 'modified' => $now],
            ['name' => 'Gaming', 'slug' => 'gaming', 'created' => $now, 'modified' => $now],
            ['name' => 'Books', 'slug' => 'books', 'created' => $now, 'modified' => $now],
            ['name' => 'Fashion', 'slug' => 'fashion', 'created' => $now, 'modified' => $now],
            ['name' => 'Home & Kitchen', 'slug' => 'home-kitchen', 'created' => $now, 'modified' => $now],
            ['name' => 'Sports & Outdoors', 'slug' => 'sports-outdoors', 'created' => $now, 'modified' => $now],
            ['name' => 'Beauty & Health', 'slug' => 'beauty-health', 'created' => $now, 'modified' => $now],
            ['name' => 'Toys & Kids', 'slug' => 'toys-kids', 'created' => $now, 'modified' => $now],
            ['name' => 'Automotive', 'slug' => 'automotive', 'created' => $now, 'modified' => $now],
            ['name' => 'Pet Supplies', 'slug' => 'pet-supplies', 'created' => $now, 'modified' => $now],
            ['name' => 'Others', 'slug' => 'others', 'created' => $now, 'modified' => $now],
        ];

        $table = $this->table('categories');
        $table->insert($data)->save();
    }
}
