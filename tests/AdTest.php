<?php

namespace Potreba\App\Tests;

use PHPUnit\Framework\TestCase;

use Potreba\App\classes\Ad;

class AdTest extends TestCase
{
    public function testGetTitle()
    {
        $ad = new Ad('Title', 'Text', 100, 'Catagory', ['name' => 'image.jpg', 'tmp_name' => 'tmp_name']);

        $this->assertEquals('Title', $ad->getTitle());
        
    }
}