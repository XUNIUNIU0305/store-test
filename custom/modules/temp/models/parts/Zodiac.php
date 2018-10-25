<?php
namespace custom\modules\temp\models\parts;

use Yii;
use yii\base\Object;
use common\ActiveRecord\TempYougaZodiacAR;
use common\ActiveRecord\TempYougaZodiacNumberAR;
use yii\base\InvalidConfigException;

class Zodiac extends Object{

    const STATUS_SELECTED = 1;
    const STATUS_UNSELECT = 0;

    const ORDER_PRICE = 858;

    public $id;


    public $selectedNumber;

    protected $AR;
    private $_selectedNumber;

    public function init(){
        if(!is_null($this->id)){
            if(!$this->AR = TempYougaZodiacAR::findOne($this->id))throw new InvalidConfigException;
        }
        if(!is_null($this->selectedNumber)){
            $this->_selectedNumber = is_array($this->selectedNumber) ? $this->selectedNumber : (array)$this->selectedNumber;
            $numberQuantity = Yii::$app->RQ->AR(new TempYougaZodiacNumberAR)->count([
                'where' => [
                    'id' => $this->_selectedNumber,
                    'selected' => self::STATUS_UNSELECT,
                ],
            ]);
            if(count($this->_selectedNumber) != $numberQuantity)throw new InvalidConfigException;
        }
    }

    public function getNumber(){
        return $this->_selectedNumber;
    }

    public function getTotalFee(){
        return (count($this->_selectedNumber) * self::ORDER_PRICE);
    }

    public static function getList(){
        return Yii::$app->RQ->AR(new TempYougaZodiacAR)->all([
            'select' => ['id', 'name'],
        ]);
    }

    public function getAllNumber(){
        return Yii::$app->RQ->AR(new TempYougaZodiacNumberAR)->all([
            'select' => ['id', 'num', 'selected'],
            'where' => [
                'temp_youga_zodiac_id' => $this->AR->id,

            ],
        ]);
    }
}
