<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddPasswordResetToUsers extends AbstractMigration
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
        $table = $this->table('users');
        $table->addColumn('password_reset_token', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('password_reset_token_expiry', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();
    }
}
