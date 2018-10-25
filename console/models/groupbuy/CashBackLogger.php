<?php
namespace console\models\groupbuy;

use Yii;

class CashBackLogger
{
    private $db;
    
    public function setDb($db)
    {
        $this->db = $db;
        return $this;
    }
    
    public function setLoggerInto()
    {
        foreach(func_get_args() as $object) {
            if(method_exists($object, 'setLogger')) {
                $object->setLogger($this);
            }
        }
        return $this;
    }
}