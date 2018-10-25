<?php
namespace common\models\parts\brand;
use common\ActiveRecord\BrandAdvAR;
use yii\base\InvalidCallException;
use yii\base\Object;
use Yii;


class BrandAdv extends Object
{
    public $id;
    private $AR;

    public function init(){
        if(!$this->id || !($this->AR = BrandAdvAR::findOne($this->id))) throw new InvalidCallException();
    }

    /**
     *====================================================
     * 批量更新
     * @param array  $advInfo
     * @param string $return
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public function setAdvInfo($advInfo = [],$return = 'false'){
        return Yii::$app->RQ->AR($this->AR)->update($advInfo, $return);
    }


}