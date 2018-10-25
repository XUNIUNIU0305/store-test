<?php
namespace common\models\parts\mobile;

use common\ActiveRecord\MobileAdvertiseAR;
use common\models\Object;
use yii\base\InvalidCallException;
use Yii;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/17
 * Time: 16:20
 */
class MobileAdvertise extends  Object
{
    public $id;
    protected  $AR;

    const STATUS_NORMAL=0;//正常启用
    const STATUS_STOP=1;//停用

    const TYPE_HOME=0;//微信首页广告位


    public function init(){
        if(!$this->id||!$this->AR=MobileAdvertiseAR::findOne(['id'=>$this->id]))throw  new InvalidCallException();
    }

    //更新状态
    public function setStatus($status){
        if($status!=self::STATUS_NORMAL&&$status!=self::STATUS_STOP){
            return false;
        }
        $this->AR->status=$status;
        return $this->AR->save();
    }

    //更新其它信息s
    public function update($type,$fileName="",$url,$sort){
        if($type!=self::TYPE_HOME){
            return false;
        }
        try{
            $data=[
                'type'=>$type,
                'url'=>$url,
                'sort'=>$sort,
            ];
            //验证图片
            if(empty($fileName)){
                $imagePath = new OSSImage([
                    'images' => ['filename' => $fileName],
                ]);
                $data['path']=current($imagePath->getPath());
            }
            return Yii::$app->RQ->AR($this->AR)->update($data,false);
        }catch (\Exception $e){
            return false;
        }
    }


}