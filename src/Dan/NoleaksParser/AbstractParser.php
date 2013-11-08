<?php

namespace Dan\NoleaksParser;
use Gedmo\Sluggable\Util\Urlizer;

abstract class AbstractParser
{
    private $filename;
    private $content;
    
    public function setFile($filename)
    {
        $this->filename = $filename;
        unset($this->content);
    }
    
    public function unsetFile()
    {
        unset($this->filename);
        unset($this->content);
    }
    
    protected function getContent()
    {
        if (!isset($this->content)) {
            $content = file_get_contents($this->filename);
            $content = strtr($content, array("\r\n" => "\n", "\r" => "\n"));
            $this->content = $content;
        }
        
        return $this->content;
    }
    
    abstract public function getData();
    
    abstract public function isValid();
    
    abstract public function getName();
    
    public function getKey()
    {
        return Urlizer::urlize($this->getName());
    }
}