<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DatabaseConnectionTest extends TestCase
{
    /**
     * Test basic database connection.
     *
     * @return void
     */
    public function testDatabaseConnection()
    {
        try {
            // Run a simple query to check the database connection
            $result = DB::select('SELECT 1');

            // Assert that the result is not empty
            $this->assertNotEmpty($result, 'Database connection failed or returned an empty result.');
        } catch (\Exception $e) {
            // If an exception is thrown, the connection has failed
            $this->fail('Database connection failed: ' . $e->getMessage());
        }
    }
}
