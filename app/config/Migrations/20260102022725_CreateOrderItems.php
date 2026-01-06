<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateOrderItems extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('order_items');
        $table->addColumn('order_id', 'integer', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('product_id', 'integer', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('price', 'decimal', [
            'default' => 0.00,
            'precision' => 10,
            'scale' => 2,
            'null' => false,
            'comment' => 'Price snapshot at purchase time',
        ]);
        $table->addColumn('quantity', 'integer', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('amount', 'decimal', [
            'default' => 0.00,
            'precision' => 10,
            'scale' => 2,
            'null' => false,
            'comment' => 'price * quantity',
        ]);
        $table->addColumn('delivery_status', 'string', [
            'default' => 'pending',
            'limit' => 50,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->create();
    }
}
