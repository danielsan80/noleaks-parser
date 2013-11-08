<?php

namespace Dan\NoleaksParser\DocType\MoneyLover\Tests;

use Dan\NoleaksParser\Tests\TestCase;
use Dan\NoleaksParser\DocType\MoneyLover\Parser;

class ParserTest extends TestCase
{
    
    public function test_isValid()
    {
        
        $file = __DIR__.'/fixtures/2012-04-02.money_valid';
        $parser = new Parser();
        $parser->setFile($file);
        $this->assertTrue($parser->isValid());
        
        $file = __DIR__.'/fixtures/2012-04-02.money_invalid1';
        $parser = new Parser();
        $parser->setFile($file);
        $this->assertFalse($parser->isValid());
        
        $file = __DIR__.'/fixtures/2012-04-02.money_invalid2';
        $parser = new Parser();
        $parser->setFile($file);
        $this->assertFalse($parser->isValid());
        
        $file = __DIR__.'/fixtures/not_xml';
        $parser = new Parser();
        $parser->setFile($file);
        $this->assertFalse($parser->isValid());
        
        $file = __DIR__.'/fixtures/31may2012.xls';
        $parser = new Parser();
        $parser->setFile($file);
        $this->assertFalse($parser->isValid());
        
        $this->assertIsValidOnlyForOneParser(__DIR__.'/fixtures/2012-04-02.money_valid');
    }
    
    public function test_getData()
    {
        
        $file = __DIR__.'/fixtures/2012-04-02.money_valid';
        $parser = new Parser();
        $parser->setFile($file);
        $data = $parser->getData();
        
        $this->assertTrue(isset($data['transactions']));
        $this->assertTrue(isset($data['balances']));
        
        $this->assertEquals(133, count($data['transactions']));
        $this->assertEquals('2012-01-13', $data['transactions'][0]['date']);
        $this->assertEquals(-22, $data['transactions'][0]['amount']);
        $this->assertEquals('gpl', $data['transactions'][0]['description']);
        $this->assertEquals(array('auto'), $data['transactions'][0]['tags']);
        
        $this->assertEquals(0, count($data['balances']));
        
    }
    
}