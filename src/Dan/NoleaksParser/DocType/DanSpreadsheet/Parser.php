<?php
namespace Dan\NoleaksParser\DocType\DanSpreadsheet;

use Dan\NoleaksParser\AbstractParser;

class Parser extends AbstractParser
{
    
    public function getData()
    {
        return array(
            'transactions' => $this->getTransactions(),
            'balances' => $this->getBalances(),
        );
    }
    
    public function isValid()
    {
        $content = $this->getContent();
        $content = explode("Date,Amount,Description,Tags\n", $content);
        if (count($content)!=2) {
            return false;
        }
        $lines = explode("\n", $content[1]);
        foreach($lines as $line) {
            if (!trim($line)) {
                continue;
            }
            $line = str_getcsv($line);
            if (count($line)!= 4) {
                return false;
            }
        }
        return true;
    }
    
    private function getBalances() {
        $content = $this->getContent();
        $lines = explode("\n", $content);
        $balances = array();
        foreach ($lines as $line) {
            if ($line == 'Date,Amount,Description,Tags') {
                return $balances;
            }
            $line = str_getcsv($line);
            if (preg_match('/Start Balance|End Balance/', $line[0])) {
                $balance = array();
                preg_match('/(?P<day>\d{2})\/(?P<month>\d{2})\/(?P<year>\d{4})/', $line[1], $matches);
                $balance['date'] = $matches['year'].'-'.$matches['month'].'-'.$matches['day'];
                preg_match('/€ (?P<amount>\d{1,3}+(.\d{3})*,\d{2})/', $line[2], $matches);
                $balance['amount'] = str_replace(',','.',str_replace('.','',$matches['amount']));
                $balances[] = $balance;
            }
        }
        
        return $balances;
    }
 
    private function getTransactions() {
        
        $content = $this->getContent();
        $content = explode("Date,Amount,Description,Tags\n", $content);
        
        $body = trim($content[1]);
        
        
        $lines = explode("\n", $body);
//        $lines = array_reverse($lines);

        $transactions = array();
        foreach($lines as $line) {
            if (!$line = trim($line)) continue;
            $line = str_getcsv($line);
            $t = array();
            preg_match('/(?P<day>\d{2})\/(?P<month>\d{2})\/(?P<year>\d{4})/', $line[0], $matches);
            $t['date'] = $t['accounting_date'] = $matches['year'].'-'.$matches['month'].'-'.$matches['day'];
            $t['accounting_date'] = $t['value_date'] = $t['date'];
            preg_match('/(?P<amount>[+-]?\d{1,3}+(.\d{3})*,\d{2})/', $line[1], $matches);
            $t['amount'] = strtr($matches['amount'], array(
                '.'=>'',
                ','=>'.',
                '€'=>'',
            ));
            $t['description'] = trim($line[2]);
            $tags = explode(',',trim($line[3]));
            foreach($tags as $tag) {
                $t['tags'][] = trim($tag);
            }
            $transactions[] = $t;
        }
        
        return $transactions;
    }
    
    
    public function getName()
    {
        return 'Dan Spreadsheet';
    }
    
}