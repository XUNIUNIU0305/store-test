<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-31
 * Time: 上午10:38
 */

namespace common\models\parts\homepage;


use common\ActiveRecord\HomepageColumnBrandAR;
use common\models\Object;

class ColumnBrand extends Object
{
    public static function getInstanceById($id)
    {
        if($model = HomepageColumnBrandAR::findOne($id)){
            return new static([
                'ar' => $model
            ]);
        }
        throw new \RuntimeException('无效ID');
    }

    public static function queryItemsByColumnId($id)
    {
        $res = HomepageColumnBrandAR::find()
            ->where(['column_id' => $id])
            ->all();
        foreach ($res as &$re){
            $re = new static(['ar' => $re]);
        }
        return $res;
    }

    public function getImgUrl()
    {
        return \Yii::$app->params['OSS_PostHost'] . '/' . $this->img;
    }

    private $supply;

    /**
     * @return SupplyUser
     */
    public function getSupply()
    {
        if($this->supply === null){
            $this->supply = SupplyUser::queryById($this->brand_id);
        }
        return $this->supply;
    }

    /**
     * 原Header
     * @return mixed
     */
    public function getHeaderImg()
    {
        return $this->getSupply()->header_img;
    }

    public function getSupplyCompanyName()
    {
        return $this->getSupply()->company_name;
    }
}