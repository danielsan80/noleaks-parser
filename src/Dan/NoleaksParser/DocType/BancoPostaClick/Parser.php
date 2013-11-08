<?php
namespace Dan\NoleaksParser\DocType\BancoPostaClick;

use Dan\NoleaksParser\AbstractParser;

class Parser extends AbstractParser
{
    
    
    public function getData()
    {
        
        $content = $this->getContent();
        $content = explode("\n\n", $content);
        
        $body = trim($content[1]);
        
        $balance = $this->getBalance();
        
        $transactions = array();

        $lines = $this->getLines();
        
        foreach($lines as $line) {
            if (!$line = trim($line)) continue;
            $line = explode("\t", $line);
            preg_match('/(?P<day>\d{2})\/(?P<month>\d{2})\/(?P<year>\d{4})/', $line[0], $matches);
            $t['date'] = $t['accounting_date'] = $matches['year'].'-'.$matches['month'].'-'.$matches['day'];
            preg_match('/(?P<day>\d{2})\/(?P<month>\d{2})\/(?P<year>\d{4})/', $line[1], $matches);
            $t['value_date'] = $matches['year'].'-'.$matches['month'].'-'.$matches['day'];
            $money =
                    - str_replace(',','.',str_replace('.','',trim($line[2])))
                    + str_replace(',','.',str_replace('.','',trim($line[3])));
            $t['amount'] = $money;
            $t['description'] = trim($line[4]);
            $t['tags'] = array();
            $transactions[] = $t;
        }
        return array(
            'transactions' => $transactions,
            'balances' => array($balance),
        );
    }
    
    public function isValid()
    {
        $content = $this->getContent();
        if (!preg_match('/Conto BancoPosta n.:/', substr($content,0,100))) {
            return false;
        }
        return (boolean)$this->getVersion();        
    }
    
    private function getVersion() {
        $content = $this->getContent();
        $lines = explode("\n", $content);
        if (preg_match('/^Conto BancoPosta n.:/', $lines[0], $matches)) {
            if (preg_match('/^Intestato a:/', $lines[1], $matches)) {
                return 'v2012';
            }
        }
        if (preg_match('/^Conto BancoPosta n.:/', $lines[1], $matches)) {
            if (preg_match('/^Intestatari:/', $lines[3], $matches)) {
                return 'v2011';
            }
        }
        return null;
    }
    
    private function getBalance() {
        $content = $this->getContent();
        $content = explode("\n\n", $content);
        $header = trim($content[0]);
        switch($this->getVersion()){
            case 'v2012':
                $balance = array();
                preg_match('/Saldo al: (?P<day>\d{2})\/(?P<month>\d{2})\/(?P<year>\d{4})/', $header, $matches);
                $balance['date'] = $matches['year'].'-'.$matches['month'].'-'.$matches['day'];
                preg_match('/Saldo disponibile: (?P<balance>\d{1,3}+(.\d{3})*,\d{2})/', $header, $matches);
                $balance['amount'] = str_replace(',','.',str_replace('.','',$matches['balance']));
                return $balance;
            case 'v2011':
                $balance = array();
                preg_match('/Saldo al: (?P<day>\d{2})\/(?P<month>\d{2})\/(?P<year>\d{4})/', $header, $matches);
                $balance['date'] = $matches['year'].'-'.$matches['month'].'-'.$matches['day'];
                preg_match('/Saldo Disponibile: (?P<balance>\d{1,3}+(.\d{3})*,\d{2})/', $header, $matches);
                $balance['amount'] = str_replace(',','.',str_replace('.','',$matches['balance']));
                return $balance;
        }
    }
    
    private function getLines() {
        $content = $this->getContent();
        $content = explode("\n\n\n", $content);
        switch($this->getVersion()){
            case 'v2012':
                $body = trim($content[1]);
                $lines = explode("\n", $body);
                unset($lines[0]);
                break;
            case 'v2011':
                $body = trim($content[2]);
                $lines = explode("\n", $body);
                break;
        }        
        $lines = array_reverse($lines);
        return $lines;
    }
    
    public function getName()
    {
        return 'Banco Posta Click';
    }
    
}