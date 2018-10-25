<?php
namespace common\models\parts\brand;
use common\ActiveRecord\BrandAdvAR;
use common\ActiveRecord\BrandHomeAR;
use yii\base\InvalidCallException;
use yii\base\Object;
use Yii;


class BrandHome extends Object
{
    public $id;
    private $AR;

    function init(){
        if(!$this->id || !($this->AR = BrandHomeAR::findOne($this->id))) throw new InvalidCallException();
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
    function setHomeInfo($advInfo = [],$return = 'false'){
        return Yii::$app->RQ->AR($this->AR)->update($advInfo, $return);
    }

    /**
     *====================================================
     * 更新状态
     * @param $status
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    function setStatus($status){
        $this->AR->status = $status;
        return $this->AR->update();
    }


}