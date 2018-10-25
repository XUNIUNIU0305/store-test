<?php

namespace common\models\temp;

use yii\base\Object;

class Groupbuy extends Object
{
    public $id;
    public $price;
    public $order_id;
    
    public function getPrice()
    {
        return $this->price;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getOrderId()
    {
        return $this->order_id;
    }
}
