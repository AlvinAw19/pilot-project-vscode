<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateLogs extends AbstractMigration
{
    /**
     * Change Method.
     *
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('logs');
        $table->addColumn('user_id', 'integer', [
            'null' => false,
        ]);
        $table->addColumn('url', 'string', [
            'limit' => 500,
            'null' => false,
        ]);
        $table->addColumn('ip_address', 'string', [
            'limit' => 45,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addIndex(['user_id']);
        $table->addForeignKey('user_id', 'users', 'id', [
            'delete' => 'CASCADE',
            'update' => 'NO_ACTION',
        ]);
        $table->create();
    }
}
