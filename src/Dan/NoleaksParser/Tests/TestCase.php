<?php

namespace Dan\NoleaksParser\Tests;

use Dan\NoleaksParser\ParserFactory;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected function assertIsValidOnlyForOneParser($validFile)
    {
        $parserFactory = new ParserFactory();
        $parsers = $parserFactory->getParsers();
        
        $file = $validFile;
        $count = 0;
        foreach($parsers as $parser) {
            $parser->setFile($file);
            $count += (int)$parser->isValid();
        }
        $this->assertEquals(1, $count);
    }
    
}