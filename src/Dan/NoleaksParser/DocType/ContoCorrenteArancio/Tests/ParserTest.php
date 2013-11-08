<?php

namespace Dan\NoleaksParser\DocType\ContoCorrenteArancio\Tests;

use Dan\NoleaksParser\Tests\TestCase;
use Dan\NoleaksParser\DocType\ContoCorrenteArancio\Parser;

class ParserTest extends TestCase
{
    public function test_isValid()
    {
        
        $file = __DIR__.'/fixtures/02jun2012_valid.xls';
        $parser = new Parser();
        $parser->setFile($file);
        $this->assertTrue($parser->isValid());
        
        $file = __DIR__.'/fixtures/02jun2012_invalid1.xls';
        $parser = new Parser();
        $parser->setFile($file);
        $this->assertFalse($parser->isValid());

        $file = __DIR__.'/fixtures/02jun2012_invalid2.xls';
        $parser = new Parser();
        $parser->setFile($file);
        $this->assertFalse($parser->isValid());
        
        $file = __DIR__.'/fixtures/31may2012_invalid3.xls';
        $parser = new Parser();
        $parser->setFile($file);
        $this->assertFalse($parser->isValid());
        
        $file = __DIR__.'/fixtures/not_xml';
        $parser = new Parser();
        $parser->setFile($file);
        $this->assertFalse($parser->isValid());
        
        $this->assertIsValidOnlyForOneParser(__DIR__.'/fixtures/02jun2012_valid.xls');
    }
    
    public function test_getData()
    {
        
        $file = __DIR__.'/fixtures/02jun2012_valid.xls';
        $parser = new Parser();
        $parser->setFile($file);
        $data = $parser->getData();
        
        $this->assertTrue(isset($data['transactions']));
        $this->assertTrue(isset($data['balances']));
        
        $this->assertEquals(15, count($data['transactions']));
        $this->assertEquals('2011-11-04', $data['transactions'][0]['date']);
        $this->assertEquals(1515, $data['transactions'][0]['amount']);
        $this->assertEquals('BONIFICO   N. 05748/13313/89219139302                         DATA ORDINE 03.11.2011 C.F. IT45612378945245765894531264 O/C OCCHIO DI QUI, QUO NOTE: PAGAMENTO STIPENDIO', $data['transactions'][0]['description']);
        $this->assertEquals(array(), $data['transactions'][0]['tags']);
        
        $this->assertEquals(0, count($data['balances']));
    }
    
}