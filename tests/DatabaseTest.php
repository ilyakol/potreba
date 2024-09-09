<?php

namespace Potreba\App\Tests;

use PHPUnit\Framework\TestCase;

use Potreba\App\Classes\Database;

use Potreba\App\classes\Ad;

class DatabaseTest extends TestCase
{
    private $db;
protected function setUp() : void
{
    $this->db = new Database();
}
public function testDatabaseConnection()
{
 $this->assertNotNull($this->db->getConnection());
} 
public function testSaveAndRetrieveAd()
{
   
    $ad = new Ad('Title', 'Text', 100, 1, ['name' => 'image.jpg', 'tmp_name' => 'tmp_name']); 

    $ad->saveAd();
    
    $this->assertNotNull($ad->getId());
}


}