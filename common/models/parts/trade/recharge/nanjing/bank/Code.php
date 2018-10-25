<?php
namespace common\models\parts\trade\recharge\nanjing\bank;

use Yii;
use yii\base\Object;
use common\ActiveRecord\BankListAR;
use common\ActiveRecord\BankCodeTinyAR;
use common\ActiveRecord\DistrictCityAR;
use common\ActiveRecord\DistrictDistrictAR;
use yii\data\ActiveDataProvider;

class Code extends Object{

    public $bank;
    public $provinceId;
    public $cityId;
    public $districtId;
    public $areaCode;
    public $string;

    protected $AR;

    private $_bankType;
    private $_areaCode;
    private $_string;
    private $_bankId;

    private $_lock = false;

    public static $singleLevelCode = [
        '1' => '1000', //北京市
        '2' => '1100', //天津市
        '9' => '2900', //上海市
        '22' => '6530', //重庆市
        '33' => '5840', //香港
        '34' => '5850', //澳门
    ];

    public static $bankTypeList = [
        '102100099996', //工商银行
        '103100000026', //农业银行
        '104100000004', //中国银行
        '105100000017', //建设银行
        '301290000007', //交通银行
        '308584000013', //招商银行
        '305100000013', //民生银行
        '309391000011', //兴业银行
        '303100000006', //光大银行
        '307584007998', //平安银行
        '403100000004', //邮储银行
        '310290000013', //浦发银行
        '302100011000', //中信银行
        '313100000013', //北京银行
        '306581000003', //广发银行
        '325290000012', //上海银行
        '313301008887', //南京银行
    ];

    public function provide(int $currentPage = 1, int $pageSize = 20, bool $asArray = true){
        $list = $currentPage * $pageSize;
        if($list > 200 || $list < 1)return false;
        return new ActiveDataProvider([
            'query' => $this->getQuery($asArray),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                ],
            ],
        ]);
    }

    private function getQuery(bool $asArray){
        $this->_lock = false;
        $this->handleString();
        $this->handleBank();
        $this->handleAreaCode();
        $activeQuery = BankCodeTinyAR::find()->
            select(['id', 'name' => 'bank_name'])->
            filterWhere(['bank_id' => $this->_bankId])->
            andFilterWhere(['area_code' => $this->_areaCode])->
            andFilterWhere(['bank_type' => $this->_bankType])->
            andFilterWhere(['like', 'bank_name', $this->_string]);
        if($asArray){
            $activeQuery->asArray();
        }
        return $activeQuery;
    }

    private function handleBank(){
        if($this->_lock)return null;
        if(empty($this->bank))return false;
        if(in_array($this->bank, self::$bankTypeList)){
            $this->_bankType = $this->bank;
            return true;
        }elseif(is_numeric($this->bank)){
            if($bank = BankListAR::findOne($this->bank)){
                $this->_bankType = $bank->bank_type;
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    private function handleAreaCode(){
        if($this->_lock)return null;
        if(is_numeric($this->areaCode) && strlen($this->areaCode) == 4){
            $this->_areaCode = $this->areaCode;
            return true;
        }
        if(is_numeric($this->provinceId) && isset(self::$singleLevelCode[$this->provinceId])){
            $this->_areaCode = self::$singleLevelCode[$this->provinceId];
            return true;
        }
        if(is_numeric($this->provinceId) && !$this->cityId && !$this->districtId){
            $cityAreaCode = Yii::$app->RQ->AR(new DistrictCityAR)->column([
                'select' => ['areacode'],
                'where' => ['district_province_id' => $this->provinceId],
            ]);
            if($cityAreaCode){
                $this->_areaCode = array_unique($cityAreaCode);
                return true;
            }
        }
        if(is_numeric($this->districtId) && ($district = DistrictDistrictAR::findOne($this->districtId))){
            $this->_areaCode = $district->areacode;
            return true;
        }
        if(is_numeric($this->cityId) && ($city = DistrictCityAR::findOne($this->cityId))){
            $areaCode = [$city->areacode];
            $districtAreaCode = Yii::$app->RQ->AR(new DistrictDistrictAR)->column([
                'select' => ['areacode'],
                'where' => ['district_city_id' => $city->id],
            ]);
            $areaCode = array_merge($areaCode, $districtAreaCode);
            $this->_areaCode = array_unique($areaCode);
            return true;
        }
        return false;
    }

    private function handleString(){
        if(empty($this->string))return false;
        if(is_numeric($this->string) && strlen($this->string) == 12){
            $this->_bankId = $this->string;
            $this->_lock = true;
            return true;
        }else{
            $this->_string = $this->string;
            return true;
        }
    }
}
