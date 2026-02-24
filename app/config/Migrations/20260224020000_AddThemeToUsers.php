<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddThemeToUsers extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('users');
        $table->addColumn('theme', 'string', [
            'limit' => 50,
            'null' => false,
            'default' => 'background1',
        ])->update();
    }
}
