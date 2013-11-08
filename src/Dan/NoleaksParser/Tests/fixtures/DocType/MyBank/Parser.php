<?php
namespace Dan\NoleaksParser\Tests\fixtures\DocType\MyBank;

use Dan\NoleaksParser\AbstractParser;

class Parser extends AbstractParser
{
    
    public function getData()
    {
        return array();
    }
    
    public function isValid()
    {
        return (boolean)($this->getContent()=='my-bank');
    }
    
    public function getName()
    {
        return 'My Bank';
    }
    
}