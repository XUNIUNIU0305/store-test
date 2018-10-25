<?php
namespace business\models\parts;

use Yii;
use yii\base\Object;
use yii\base\InvalidConfigException;
use common\ActiveRecord\BusinessRoleAR;

class Role extends Object{

    const SUPER_ADMIN = 1;
    const ADMIN = 2;
    const TOP = 3;
    const SECONDARY = 4;
    const TERTIARY = 5;
    const QUATERNARY = 6;
    const FIFTH_LEADER = 7;
    const FIFTH_COMMISSAR = 8;
    const UNDEFINED = 9;

    public $id;

    private $_id;

    protected $AR;

    public function init(){
        if(!$this->AR = BusinessRoleAR::findOne($this->id))throw new InvalidConfigException('unavailable role id');
        $this->_id = $this->id;
    }

    public function getRole(){
        return $this->_id;
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

    public function getLevel(){
        return $this->AR->level;
    }

    public function getIsAreaRole(){
        return in_array($this->id, [
            self::TOP,
            self::SECONDARY,
            self::TERTIARY,
            self::QUATERNARY,
            self::FIFTH_LEADER,
            self::FIFTH_COMMISSAR,
        ]);
    }
}
