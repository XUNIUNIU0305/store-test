<?php
/**
 * User: JiangYi
 * Date: 2017/5/28
 * Time: 15:32
 * Desc:
 */

namespace common\models\parts\quality;


use business\models\parts\Area;
use common\ActiveRecord\BusinessAreaTechnicanAR;
use common\ActiveRecord\QualityOrderAR;
use common\components\handler\quality\BusinessAreaTechnicanHandler;
use common\models\Object;
use yii\base\InvalidCallException;
use Yii;

class BusinessAreaTechnican extends  Object
{

    public $id;

    protected  $AR;

    public function init(){
         if(!$this->id||!$this->AR=BusinessAreaTechnicanAR::findOne($this->id))throw new InvalidCallException();

    }




    //获取相关质保单数量
    public function getQulityQuantity(){
        return Yii::$app->RQ->AR(new QualityOrderAR())->count([
            'where'=>['custom_user_technician_id'=>$this->id],
        ]);
    }


    //获取绑定列表
    public function getQualityOrders($pageSize,$currentPage){
        return BusinessAreaTechnicanHandler::getList($pageSize,$currentPage,null,$this);
    }



    /**
     * Author:JiangYi
     * Date:2017/5/28
     * Desc:设置配置信息
     * @param Area $area
     * @return mixed
     */
    public function setArea(Area $area)
    {
        $this->AR->business_area_id = $area->id;
        return $this->AR->save();
    }


    /**
     *====================================================
     * 软删除
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public function setIsDel(){
        $this->AR->is_del = BusinessAreaTechnicanAR::YES_DEL;
        return $this->AR->update();
    }

    /**
     * Author:JiangYi
     * Date:2017/5/28
     * Desc:更新技师相关信息
     * @param $name
     * @param string $mobile
     * @param string $remark
     * @param string $return
     * @return mixed
     */
    public function setTechnicanInfo($name, $mobile="", $remark="",Area $area=null, $return="throw"){
        $data=['name'=>$name];
        if(!empty($mobile)){
            $data['mobile']=$mobile;
        }
        if(!empty($remark)){
            $data['remark']=$remark;
        }
        if($area!=null){
            $data['business_area_id']=$area->id;
        }
        return Yii::$app->RQ->AR($this->AR)->update($data,$return);

    }

    public function getName(){
        return $this->AR->name;
    }

}