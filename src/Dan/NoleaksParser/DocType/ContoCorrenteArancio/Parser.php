<?php
namespace Dan\NoleaksParser\DocType\ContoCorrenteArancio;

use Dan\NoleaksParser\AbstractParser;

class Parser extends AbstractParser
{
    
    private $xml;
    
    public function getData()
    {
        
        $xml = $this->getXml();
        
        $nodeTransactions = $xml->xpath("/root/table/tr");
        
        $transactions = array();
        foreach($nodeTransactions as $nodeTransaction) {
            $t = array();
            $nodeTds = $nodeTransaction->xpath("./td[@style]");
            if (count($nodeTds)) {
                continue;
            }
            $nodeTds = $nodeTransaction->xpath("./td[@colspan]");
            if (count($nodeTds)) {
                continue;
            }
            $nodeTds = $nodeTransaction->xpath("./td");
            preg_match('/(?P<day>\d{2})\/(?P<month>\d{2})\/(?P<year>\d{4})/', (string)$nodeTds[0], $matches);
            $t['date'] = $t['accounting_date'] = $matches['year'].'-'.$matches['month'].'-'.$matches['day'];
            preg_match('/(?P<day>\d{2})\/(?P<month>\d{2})\/(?P<year>\d{4})/', (string)$nodeTds[1], $matches);
            $t['value_date'] = $matches['year'].'-'.$matches['month'].'-'.$matches['day'];
            $t['_type'] = (string)$nodeTds[2];
            $t['description'] = (string)$nodeTds[3];
            $t['amount'] = + trim(strtr((string)$nodeTds[4], array(
                    '.' => '',
                    ',' => '.',
                    'euro' => '',
                )));
            $t['tags'] = array();
            
            $transactions[] = $t;
        }
        
        $transactions = array_reverse($transactions);
        
        //ContoArancio files cover all transactions, so you don't need to take some balances
//        $nodes = $xml->xpath("/root/table/tr/td[@colspan='5']");
//        foreach($nodes as $node) {
//            if (!($b = $node->xpath('./b'))) {
//                continue;
//            }
//            $b = $b[0];
//            if (trim((string)$b) != 'Saldo disponibile al') {
//                continue;
//            }
//            preg_match('/(?P<day>\d{2})\/(?P<month>\d{2})\/(?P<year>\d{4}) (?P<balance>\d{1,3}+(.\d{3})*,(\d{2})?) Euro/', (string)$node, $matches);
//            $balance = array(
//                'date' => $matches['year'].'-'.$matches['month'].'-'.$matches['day'],
//                $balance['amount'] = str_replace(',','.',str_replace('.','',$matches['balance'])),
//            );
//            break;
//        }

        return array(
            'transactions' => $transactions,
            'balances' => array(),
        );
    }
    
    public function isValid()
    {
        $content = $this->getContent();
        if (!preg_match('/<style>[ ]*.excelText/', substr($content,0,100))) {
            return false;
        }
        
        if (!preg_match('/Consultazioni:(.*)Conto Corrente Arancio(.*)Saldo contabile/s', $content)) {
            return false;
        }
        
        $xml = $this->getXml();

        if (!$xml) {
            return false;
        }
        
        $root = $xml->xpath("/root");
        if (count($root)!=1) {
            return false;
        }
        
        $table = $xml->xpath("/root/table");
        if (count($table)!=1) {
            return false;
        }
        
        if (strpos($this->getContent(), 'Giorno dal quale iniziano a maturare gli interessi.')===false) {
            return false;
        }
        
        if (strpos($this->getContent(), 'Conto Corrente Arancio n.:')===false) {
            return false;
        }
        
        return true;

    }
    
    public function getName()
    {
        return 'Conto Corrente Arancio';
    }
    
    private function getXml() {
        if (!isset($this->xml)) {
            $content = $this->getContent();
            $content = utf8_encode($content);
            $content = strtr($content, array('&euro;' => 'euro'));
            $content = '<root>'.$content.'</root>';
            try {
                $this->xml = new \SimpleXMLElement($content);
            } catch (Exception $e) {
                return null;
            }
        }
        return $this->xml;
    }
    
}