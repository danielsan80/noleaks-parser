<?php

namespace Dan\NoleaksParser\Tests;

use Dan\NoleaksParser\ParserFactory;

class ParserFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function test_createParser()
    {
        $factory = new ParserFactory(__DIR__.'/fixtures/docs');
        $factory->setDocTypePath(__DIR__.'/fixtures/DocType');
        $factory->setDocTypeBaseNamespace('Dan\\NoleaksParser\\Tests\\fixtures\\DocType');

        $parser = $factory->createParser('mybank_april.txt');
        $this->assertInstanceOf('Dan\\NoleaksParser\\AbstractParser', $parser);
        $this->assertEquals('My Bank', $parser->getName());
        $this->assertEquals('my-bank', $parser->getKey());
        
        $parser = $factory->createParser('mycreditcard_may.txt');
        $this->assertInstanceOf('Dan\\NoleaksParser\\AbstractParser', $parser);
        $this->assertEquals('My Credit Card', $parser->getName());
        $this->assertEquals('my-credit-card', $parser->getKey());
    }
}