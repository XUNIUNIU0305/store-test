<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/3/14
 * Time: 下午6:46
 */

namespace admin\models\parts\template;

use common\ActiveRecord\AdminFloorAR;
use common\ActiveRecord\AdminFloorGoodsAR;
use yii\base\Object;
use Yii;

class Floor extends Object
{

    //新增
    public function add($obj)
    {
        $exists = Yii::$app->RQ->AR(new AdminFloorAR())->exists([
            'where' => 'sort = ' . $obj->getSort(),
            'limit' => 1,
        ]);
        if (!$exists)
        {

            $data = [
                'sort' => $obj->getSort(),
                'title_ch' => $obj->titleCh,
                'title_en' => $obj->titleEn,
                'floor_color' => $obj->floorColor,
                'title_simple' => $obj->titleSimple,
                'floor_url' => $obj->floorUrl,
                'file_name' => $obj->fileName,
            ];
            return Yii::$app->RQ->AR(new AdminFloorAR())->insert($data);

        }

        return false;
    }

    //删除
    public function delete($obj)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try
        {
            //删除楼层
            Yii::$app->RQ->AR(AdminFloorAR::findOne(['id' => $obj->id]))->delete();

            //删除楼层下的商品
            $exists = Yii::$app->RQ->AR(new AdminFloorGoodsAR())->exists([
                'where' => "fid = $obj->id ",
                'limit' => 1,
            ]);
            if ($exists)
            {
                AdminFloorGoodsAR::deleteAll([
                    'fid' => $obj->id,
                ]);
            }

            $transaction->commit();
            return true;

        }
        catch (\Exception $e)
        {
            $transaction->rollBack();
            return false;
        }
    }


}



