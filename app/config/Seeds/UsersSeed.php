<?php
declare(strict_types=1);

use Cake\Auth\DefaultPasswordHasher;
use Migrations\AbstractSeed;

/**
 * Users seed.
 */
class UsersSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * @return void
     */
    public function run(): void
    {
        $hasher = new DefaultPasswordHasher();
        $now = date('Y-m-d H:i:s');

        $data = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => $hasher->hash('password123'),
                'address' => '123 Admin Street, KL',
                'description' => 'System administrator',
                'role' => 'admin',
                'created' => $now,
                'modified' => $now,
            ],
            [
                'name' => 'Alice Seller',
                'email' => 'alice@example.com',
                'password' => $hasher->hash('password123'),
                'address' => '45 Jalan Bukit Bintang, KL',
                'description' => 'Premium electronics & gadgets seller',
                'role' => 'seller',
                'created' => $now,
                'modified' => $now,
            ],
            [
                'name' => 'Bob Seller',
                'email' => 'bob@example.com',
                'password' => $hasher->hash('password123'),
                'address' => '78 Jalan Petaling, KL',
                'description' => 'Books, stationery & lifestyle products',
                'role' => 'seller',
                'created' => $now,
                'modified' => $now,
            ],
            [
                'name' => 'Charlie Buyer',
                'email' => 'charlie@example.com',
                'password' => $hasher->hash('password123'),
                'address' => '12 Taman Desa, KL',
                'description' => null,
                'role' => 'buyer',
                'created' => $now,
                'modified' => $now,
            ],
            [
                'name' => 'Diana Buyer',
                'email' => 'diana@example.com',
                'password' => $hasher->hash('password123'),
                'address' => '99 Bangsar South, KL',
                'description' => null,
                'role' => 'buyer',
                'created' => $now,
                'modified' => $now,
            ],
            [
                'name' => 'Eve Buyer',
                'email' => 'eve@example.com',
                'password' => $hasher->hash('password123'),
                'address' => '55 Mont Kiara, KL',
                'description' => null,
                'role' => 'buyer',
                'created' => $now,
                'modified' => $now,
            ],
        ];

        $table = $this->table('users');
        $table->insert($data)->save();
    }
}
