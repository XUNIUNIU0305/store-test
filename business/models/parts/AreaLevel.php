<?php
namespace business\models\parts;

use Yii;
use yii\base\Object;
use yii\base\InvalidConfigException;
use common\ActiveRecord\BusinessAreaLevelAR;

class AreaLevel extends Object{

    public $level;

    protected $AR;

    private static $_list;

    public function init(){
        if(!$this->AR = BusinessAreaLevelAR::findOne(['level' => $this->level]))throw new InvalidConfigException('undefined level');
    }

    public function getName(){
        return $this->AR->name;
    }

    public function setName(string $name, $return = 'throw'){
        if(empty($name))return Yii::$app->EC->callback($return, 'string');
        return Yii::$app->RQ->AR($this->AR)->update([
            'name' => $name,
        ]);
    }

    public function getHasChild(){
        return Yii::$app->RQ->AR(new BusinessAreaLevelAR)->exists([
            'select' => ['id'],
            'where' => ['level' => $this->level + 1],
            'limit' => 1,
        ]);
    }

    public function getChildLevel(){
        if($this->getHasChild()){
            return ($this->level + 1);
        }else{
            return false;
        }
    }

    public static function getLevelList(){
        if(is_null(self::$_list)){
            $list = Yii::$app->RQ->AR(new BusinessAreaLevelAR)->all([
                'select' => ['level', 'name'],
            ]);
            self::$_list = array_column($list, 'name', 'level');
        }
        return self::$_list;
    }
}
