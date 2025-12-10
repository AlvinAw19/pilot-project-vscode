<?php
declare(strict_types=1);

namespace App\Test\TestCase\Database;

use Cake\Database\Driver\Mysql;
use Cake\Datasource\ConnectionManager;
use Cake\TestSuite\TestCase;

/**
 * Class DatabaseConnectionTest
 */
class DatabaseConnectionTest extends TestCase
{
    /**
     * This test ensures that the 'test' datasource is using the MySQL driver
     *
     * @return void
     */
    public function testDatabaseConnectionIsMysqlForTest(): void
    {
        // Manages and loads instances of Connection for 'test'
        $connection = ConnectionManager::get('test');
        $driver = $connection->getDriver();

        // Assert the driver is an instance of Cake\Database\Driver\Mysql
        $this->assertInstanceOf(Mysql::class, $driver, 'The test database driver is not an instance of Mysql.');
    }
}
