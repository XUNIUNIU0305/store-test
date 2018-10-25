<?php
namespace common\models\parts\trade\recharge\nanjing\bank;

use Yii;
use common\models\ObjectAbstract;
use common\ActiveRecord\BankListAR;
use common\ActiveRecord\BankCodeTinyAR;
use yii\base\InvalidConfigException;

class Bank extends ObjectAbstract{

    public $id;
    public $type;

    protected $AR;

    public function init(){
        if($this->id){
            if(!$this->AR = BankListAR::findOne($this->id))throw new InvalidConfigException('unavailable id');
        }elseif($this->type){
            if(!$this->AR = BankListAR::findOne(['bank_type' => $this->type]))throw new InvalidConfigException('unavailable type');
        }
        $this->id = $this->AR->id;
        $this->type = $this->AR->bank_type;
    }

    public function getLogoImageUrl(){
        return Yii::$app->params['OSS_PostHost'] . $this->AR->logo_image;
    }

    protected function _gettingList() : array{
        return [
            'bankName',
            'bankType',
        ];
    }

    protected function _settingList() : array{
        return [];
    }

    public static function getList(array $params = null){
        if(is_null($params))$params = ['id'];
        $query = [
            'id' => 'id',
            'name' => 'bank_name',
            'type' => 'bank_type',
            'image' => 'logo_image',
        ];
        $field = [];
        foreach($params as $param){
            if(isset($query[$param])){
                $field[$param] = $query[$param];
            }
        }
        if(empty($field))return [];
        $list = Yii::$app->RQ->AR(new BankListAR)->all([
            'select' => $field,
        ]);
        if(in_array('image', $params)){
            return array_map(function($one){
                $one['image'] = Yii::$app->params['OSS_PostHost'] . $one['image'];
                return $one;
            }, $list);
        }else{
            return $list;
        }
    }

    public static function getBankType($branchId){
        return Yii::$app->RQ->AR(new BankCodeTinyAR)->scalar([
            'select' => ['bank_type'],
            'where' => ['bank_id' => $branchId],
        ]) ? : false;
    }
}
