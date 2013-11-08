<?php
namespace Dan\NoleaksParser\DocType\MoneyLover;

use Dan\NoleaksParser\AbstractParser;

class Parser extends AbstractParser
{
    
    private $xml;
    
    public function getData()
    {
        
        $xml = $this->getXml();
        
        $nodeCategories = $xml->xpath("/export-database/table[@name='categories']/row");
        
        $tags = array();
        foreach($nodeCategories as $nodeCategory) {
            $id = $nodeCategory->xpath("./col[@name='id']");
            $name = $nodeCategory->xpath("./col[@name='name']");
            
            $id = (string)$id[0];
            $name = strtolower((string)$name[0]);

            $tags[$id] = $name;
        }
        
        $nodeTransactions = $xml->xpath("/export-database/table[@name='transactions']/row");
        
        $transactions = array();
        foreach($nodeTransactions as $nodeTransaction) {
            $t = array();
            
            $t['id'] = $nodeTransaction->xpath("./col[@name='id']");
            $t['description'] = $nodeTransaction->xpath("./col[@name='name']");
            $t['amount'] = $nodeTransaction->xpath("./col[@name='amount']");
            $t['_type'] = $nodeTransaction->xpath("./col[@name='type']");
            $t['_created_date'] = $nodeTransaction->xpath("./col[@name='created_date']");
            $t['date'] = $nodeTransaction->xpath("./col[@name='displayed_date']");
            $t['_cat_id'] = $nodeTransaction->xpath("./col[@name='cat_id']");
            $t['_note'] = $nodeTransaction->xpath("./col[@name='note']");
            $t['_status'] = $nodeTransaction->xpath("./col[@name='status']");
            $t['_user_id'] = $nodeTransaction->xpath("./col[@name='user_id']");
            
            $t['id'] = (string)$t['id'][0];
            $t['description'] = trim((string)$t['description'][0]);
            $t['amount'] = (string)$t['amount'][0];
            $t['_type'] = (string)$t['_type'][0];
            $t['date'] = (string)$t['date'][0];
            $t['_cat_id'] = (string)$t['_cat_id'][0];
            $t['_note'] = (string)$t['_note'][0];
            $t['_status'] = (string)$t['_status'][0];
            $t['_user_id'] = (string)$t['_user_id'][0];
            
            if ($t['_cat_id']) {
                $t['tags'] = array($tags[$t['_cat_id']]);
            } else {
                $t['tags'] = array();
            }
            
            switch ($t['_type']) {
                case '1': // Entrances
                    $t['amount'] = + $t['amount'];
                    break;
                case '2': // Expenses
                    $t['amount'] = - $t['amount'];
                    break;
                case '3': // Debits
                    $t['amount'] = + $t['amount'];
                    break;
                case '4': // Credits
                    $t['amount'] = - $t['amount'];
                    break;
            }
            if ($t['_type']==3 || $t['_type']==4) {
                continue;
            }
            

            $transactions[] = $t;
        }
        
        return array(
            'transactions' => $transactions,
            'balances' => array(), //@todo
        );
    }
    
    public function isValid()
    {
        $content = $this->getContent();
        if (!preg_match('/<export-database([^>]*)>/', substr($content,0,100))) {
            return false;
        }
        $xml = $this->getXml();

        if (!$xml) {
            return false;
        }
        
        $root = $xml->xpath("/export-database");
        if (count($root)!=1) {
            return false;
        }
        
        $transactions = $xml->xpath("/export-database/table[@name='transactions']");
        if (count($transactions)!=1) {
            return false;
        }
        
        return true;

    }
    
    public function getName()
    {
        return 'Money Lover';
    }
    
    private function getXml() {
        $content = $this->getContent();
        if (!isset($this->xml)) {
            try {
                $this->xml = new \SimpleXMLElement($content);
            } catch (Exception $e) {
                return null;
            }
        }
        return $this->xml;
    }
    
}