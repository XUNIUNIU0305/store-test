<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/3/14
 * Time: 下午6:46
 */

namespace admin\models\parts\template;

use common\ActiveRecord\AdminFloorGoodsAR;
use yii\base\Object;
use Yii;

class FloorGoods extends Object
{

    //新增
    public function add($obj)
    {
        if (!Yii::$app->RQ->AR(new AdminFloorGoodsAR())->exists([
            'where' => "(sort = {$obj->getSort()} or good_id = {$obj->goodId}) and fid = {$obj->fid}",
            'limit' => 1,
        ])
        )
        {
            $data = [
                'sort' => $obj->getSort(),
                'fid' => $obj->fid,
                'good_name' => $obj->goodName,
                'sale_one' => $obj->saleOne,
                'sale_two' => $obj->saleTwo,
                'good_id' => $obj->goodId,
                'good_url' => $obj->goodUrl,
                'file_name' => $obj->fileName,
            ];

            return Yii::$app->RQ->AR(new AdminFloorGoodsAR())->insert($data);

        }
        return false;
    }

    //删除
    public function delete($obj)
    {
        return Yii::$app->RQ->AR(AdminFloorGoodsAR::findOne($obj->id))->delete();
    }


}



