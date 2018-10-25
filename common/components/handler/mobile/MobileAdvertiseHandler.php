<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/17
 * Time: 16:56
 */

namespace common\components\handler\mobile;


use common\ActiveRecord\MobileAdvertiseAR;
use common\components\handler\Handler;
use common\models\parts\mobile\MobileAdvertise;
use yii\data\ActiveDataProvider;
use Yii;

class MobileAdvertiseHandler extends  Handler
{

    //获取广告列表
    public static function search($pageSize,$currentPage,$status=null,$type=null,$orderBy=['id'=>SORT_DESC]){
        //配置参数
       $parameter=[':status'=>$status,':type'=>$type];
       //组合查询条件
       $where="1";
       if($status!==null){
           $where.=" and status=':status'";
       }
       if($type!==null){
           $where.=" and type=':type'";
       }
       //取分页
        $currentPage = (int)$currentPage or $currentPage = 1;
        $pageSize = (int)$pageSize or $pageSize = 1;
        return new ActiveDataProvider([
            'query' => MobileAdvertiseAR::find()->select('id')->where($where,$parameter)->asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => $orderBy,
            ],
        ]);
    }

    //创建
    public static  function create($type,$path,$url,$sort,$status){
        //检测数据
        if($type!=MobileAdvertise::TYPE_HOME){
            return false;
        }
        if($status!=MobileAdvertise::STATUS_NORMAL&&$status!=MobileAdvertise::STATUS_STOP){
            return false;
        }
        return Yii::$app->RQ->AR(new MobileAdvertiseAR())->insert([
            'type'=>$type,
            'path'=>$path,
            'url'=>$url,
            'sort'=>$sort,
            'status'=>$status,
        ],false);
    }

    //删除广告位
    public static function delete(MobileAdvertise $advertise){
        return Yii::$app->RQ->AR(MobileAdvertiseAR::findOne(['id'=>$advertise->id]))->delete(false);
    }

}