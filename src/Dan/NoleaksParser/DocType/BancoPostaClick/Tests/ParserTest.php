<?php

namespace Dan\NoleaksParser\DocType\BancoPostaClick\Tests;

use Dan\NoleaksParser\Tests\TestCase;
use Dan\NoleaksParser\DocType\BancoPostaClick\Parser;

class ParserTest extends TestCase
{
    public function test_isValid()
    {
        $file = __DIR__.'/fixtures/03apr2012_valid.txt';
        $parser = new Parser();
        $parser->setFile($file);
        $this->assertTrue($parser->isValid());
        
        $file = __DIR__.'/fixtures/09lug2011_valid.txt';
        $parser = new Parser();
        $parser->setFile($file);
        $this->assertTrue($parser->isValid());
        
        $file = __DIR__.'/fixtures/03apr2012_invalid.txt';
        $parser = new Parser();
        $parser->setFile($file);
        $this->assertFalse($parser->isValid());

        $this->assertIsValidOnlyForOneParser(__DIR__.'/fixtures/03apr2012_valid.txt');
    }
    
    public function test_getData1()
    {
        
        $file = __DIR__.'/fixtures/03apr2012_valid.txt';
        $parser = new Parser();
        $parser->setFile($file);
        $data = $parser->getData();
        
        $this->assertTrue(isset($data['transactions']));
        $this->assertTrue(isset($data['balances']));
        
        $this->assertEquals(40, count($data['transactions']));
        $this->assertEquals('2012-01-14', $data['transactions'][0]['date']);
        $this->assertEquals(-300, $data['transactions'][0]['amount']);
        $this->assertEquals('PRELIEVO POSTAMAT NOSTRO SPORTELLO AUTOMATICO 13/01/2012 16.11 ATM N.  569 UFFICIO POSTALE RICCIONE 1                     CARTA    12345', $data['transactions'][0]['description']);
        $this->assertEquals(array(), $data['transactions'][0]['tags']);
        
        $this->assertEquals(1, count($data['balances']));
        $this->assertEquals('2012-04-03', $data['balances'][0]['date']);
        $this->assertEquals(391.77, $data['balances'][0]['amount']);
    }
    
    public function test_getData2()
    {
        $file = __DIR__.'/fixtures/09lug2011_valid.txt';
        $parser = new Parser();
        $parser->setFile($file);
        $data = $parser->getData();
        
        $this->assertTrue(isset($data['transactions']));
        $this->assertTrue(isset($data['balances']));
        
        $this->assertEquals(40, count($data['transactions']));
        $this->assertEquals('2011-04-06', $data['transactions'][0]['date']);
        $this->assertEquals(-1.8, $data['transactions'][0]['amount']);
        $this->assertEquals('PEDAGGIO AUTOSTRADALE 01/04/2011 00.00 AUTOSTRADE PER L\'ITALI RIMINI SUD/00 ITA                   CARTA    16137', $data['transactions'][0]['description']);
        $this->assertEquals(array(), $data['transactions'][0]['tags']);
        
        $this->assertEquals(1, count($data['balances']));
        $this->assertEquals('2011-07-09', $data['balances'][0]['date']);
        $this->assertEquals(664.63, $data['balances'][0]['amount']);
    }
    
}