<?php

namespace Dan\NoleaksParser\DocType\DanSpreadsheet\Tests;

use Dan\NoleaksParser\Tests\TestCase;
use Dan\NoleaksParser\DocType\DanSpreadsheet\Parser;

class ParserTest extends TestCase
{
    
    public function test_isValid()
    {
        $file = __DIR__.'/fixtures/jun2012_valid.csv';
        $parser = new Parser();
        $parser->setFile($file);
        $this->assertTrue($parser->isValid());
        
        $file = __DIR__.'/fixtures/jun2012_onlytransactions_valid.csv';
        $parser = new Parser();
        $parser->setFile($file);
        $this->assertTrue($parser->isValid());
        
        $file = __DIR__.'/fixtures/jun2012_invalid1.csv';
        $parser = new Parser();
        $parser->setFile($file);
        $this->assertFalse($parser->isValid());
 
        $file = __DIR__.'/fixtures/jun2012_invalid2.csv';
        $parser = new Parser();
        $parser->setFile($file);
        $this->assertFalse($parser->isValid());
        
        $this->assertIsValidOnlyForOneParser(__DIR__.'/fixtures/jun2012_valid.csv');
    }
    
    public function test_getData()
    {
        
        $file = __DIR__.'/fixtures/jun2012_valid.csv';
        $parser = new Parser();
        $parser->setFile($file);
        $data = $parser->getData();
        
        $this->assertTrue(isset($data['transactions']));
        $this->assertTrue(isset($data['balances']));
        
        $this->assertEquals(18, count($data['transactions']));
        $this->assertEquals('2012-06-06', $data['transactions'][0]['date']);
        $this->assertEquals('2012-06-06', $data['transactions'][0]['accounting_date']);
        $this->assertEquals('2012-06-06', $data['transactions'][0]['value_date']);
        $this->assertEquals(-1.30, $data['transactions'][0]['amount']);
        $this->assertEquals('Pedaggio autostradale 01/06/2012 Rimini nord', $data['transactions'][0]['description']);
        $this->assertEquals(array('autostrada', 'auto'), $data['transactions'][0]['tags']);
        
        $this->assertEquals(2, count($data['balances']));
        $this->assertEquals('2012-05-31', $data['balances'][0]['date']);
        $this->assertEquals('2012-06-30', $data['balances'][1]['date']);
        $this->assertEquals(1628.29, $data['balances'][0]['amount']);
        $this->assertEquals(781.06, $data['balances'][1]['amount']);
    }
}