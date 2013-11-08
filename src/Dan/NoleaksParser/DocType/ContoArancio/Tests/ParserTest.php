<?php

namespace Dan\NoleaksParser\DocType\ContoArancio\Tests;

use Dan\NoleaksParser\Tests\TestCase;
use Dan\NoleaksParser\DocType\ContoArancio\Parser;

class ParserTest extends TestCase
{
    public function test_isValid()
    {
        
        $file = __DIR__.'/fixtures/31may2012_valid.xls';
        $parser = new Parser();
        $parser->setFile($file);
        $this->assertTrue($parser->isValid());
        
        $file = __DIR__.'/fixtures/31may2012_invalid1.xls';
        $parser = new Parser();
        $parser->setFile($file);
        $this->assertFalse($parser->isValid());

        $file = __DIR__.'/fixtures/31may2012_invalid2.xls';
        $parser = new Parser();
        $parser->setFile($file);
        $this->assertFalse($parser->isValid());
        
        $file = __DIR__.'/fixtures/02jun2012_invalid3.xls';
        $parser = new Parser();
        $parser->setFile($file);
        $this->assertFalse($parser->isValid());
        
        $file = __DIR__.'/fixtures/not_xml';
        $parser = new Parser();
        $parser->setFile($file);
        $this->assertFalse($parser->isValid());
        
        $this->assertIsValidOnlyForOneParser(__DIR__.'/fixtures/31may2012_valid.xls');
        
    }
    
    public function test_getData()
    {
        
        $file = __DIR__.'/fixtures/31may2012_valid.xls';
        $parser = new Parser();
        $parser->setFile($file);
        $data = $parser->getData();
        
        $this->assertTrue(isset($data['transactions']));
        $this->assertTrue(isset($data['balances']));
        
        $this->assertEquals(56, count($data['transactions']));
        $this->assertEquals('2008-04-28', $data['transactions'][0]['date']);
        $this->assertEquals(10, $data['transactions'][0]['amount']);
        $this->assertEquals('Accredito', $data['transactions'][0]['description']);
        $this->assertEquals(array(), $data['transactions'][0]['tags']);
        
        $this->assertEquals(0, count($data['balances']));
        
    }
    
}