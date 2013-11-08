<?php

namespace Dan\NoleaksParser;

/**
 * The factory for Parser objects 
 */
class ParserFactory
{
    
    private $docsPath;
    private $docTypePath;
    private $docTypeBaseNamespace;
    private $parsers = array();

    
    public function __construct($docsPath = null)
    {
        $this->docsPath = $docsPath;
        $this->docTypePath = __DIR__.'/DocType';
        $this->docTypeBaseNamespace = 'Dan\\NoleaksParser\\DocType';
    }
    
    public function setDocTypePath($path)
    {
        $this->docTypePath = $path;
    }
    public function setDocTypeBaseNamespace($namespace)
    {
        $this->docTypeBaseNamespace = $namespace;
    }
    
    public function createParser($filename)
    {
        $parsers = $this->getParsers();
        
        if ($this->docsPath) {
            $filename = $this->docsPath.'/'.$filename;
        }
        
        $found = false;
        foreach($parsers as $parser) {
            $parser->setFile($filename);
            if ($parser->isValid()) {
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            throw new \Exception('It is not defined a parser for '.$filename);
        }
        
        return $parser;
    }
    
    private function loadParsers()
    {
        if (!($dir = opendir($this->docTypePath))) {
            throw new \Exception('It is not possible to open the driver directory');
        }
        while (($docType = readdir($dir)) !== false) {
            if ($docType=='.' || $docType=='..') {
                continue;
            }
            $parserClass = $this->docTypeBaseNamespace.'\\'.$docType.'\\Parser';
            $parser = new $parserClass();
            $this->parsers[$parser->getKey()] = $parser;
        }
        closedir($dir);
    }
    
    public function getParsers()
    {
        if (!$this->parsers) {
            $this->loadParsers();
        }
        return $this->parsers;
    }
}