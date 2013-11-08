<?php
namespace Dan\NoleaksParser\Tests\fixtures\DocType\MyCreditCard;

use Dan\NoleaksParser\AbstractParser;

class Parser extends AbstractParser
{
    
    public function getData()
    {
        return array();
    }
    
    public function isValid()
    {
        return (boolean)($this->getContent()=='my-credit-card');
    }
    
    public function getName()
    {
        return 'My Credit Card';
    }
    
}