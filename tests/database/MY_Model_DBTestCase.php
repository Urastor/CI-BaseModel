<?php

abstract class MY_Model_DBTestCase extends \PHPUnit_Extensions_Database_TestCase
{

    private $conn = null;
    
    // Setup Database-Connection
    final public function getConnection()
    {
        if ($this->conn === null) {
            try {
                
                $pdo = new \PDO('mysql:host=localhost;dbname=mymodel', 'root', '1234');
                $this->conn = $this->createDefaultDBConnection($pdo);
            } catch (\PDOException $e) {
                $this->fail('A DB-Connection could not be created.');
            }
        }
        
        return $this->conn;
    }
}
