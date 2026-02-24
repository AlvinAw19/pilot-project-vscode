<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddDeletedToCartItems extends AbstractMigration
{
    /**
     * Change Method.
     *
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('cart_items');
        $table->addColumn('deleted', 'datetime', [
            'default' => null,
            'null' => true,
        ])->update();
    }
}
