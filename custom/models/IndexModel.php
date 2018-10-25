<?php
namespace custom\models;

use common\ActiveRecord\AdminBrandWallAR;
use common\ActiveRecord\AdminFloorAR;
use common\ActiveRecord\AdminFloorGoodsAR;
use common\ActiveRecord\AdminImageCarouselAR;
use common\ActiveRecord\ProductSKUAR;
use common\traits\CheckReturnTrait;
use Yii;
use common\models\Model;
use common\models\parts\district\District;

class IndexModel extends Model
{

    use CheckReturnTrait;

    public static function getUserInfo()
    {
        if (Yii::$app->user->isGuest)
        {
            return [];
        }
        else
        {
            $district = new District([
                'districtId' => Yii::$app->user->identity->district_district_id,
                'cityId' => Yii::$app->user->identity->district_city_id,
                'provinceId' => Yii::$app->user->identity->district_province_id,
            ]);
            $districtName = $district->name ? $district->name : '';
            $cityName = ($city = $district->city) ? $city->name : '';
            $provinceName = ($province = $district->province) ? $province->name : '';
            $businessArea = [];
            foreach (Yii::$app->CustomUser->CurrentUser->getArea()->getFullArea() as $key => $item) {
                $businessArea[$key] = $item->getName();
            }
            return [
                'name' => Yii::$app->user->identity->account,
                'shop_name'=>Yii::$app->CustomUser->CurrentUser->getShopName(),
                'district' => $provinceName . $cityName . $districtName,
                'default_address' => strval(Yii::$app->CustomUser->address->defaultAddress),
                'nick_name'=>Yii::$app->CustomUser->CurrentUser->getNickName(),
                'mobile'=>Yii::$app->CustomUser->CurrentUser->getMobile(),
                'header_img'=>Yii::$app->CustomUser->CurrentUser->getHeaderImg(),
                'level'=>Yii::$app->CustomUser->CurrentUser->level,
                'business_area' => $businessArea,
                'tag' => (new \custom\models\parts\temp\UserProductOrderLimit\UserProductLimit)->validateProductLimit(Yii::$app->user->identity->account) ? 1 : 0,
            ];
        }
    }


    //获取轮播图信息

    public static function getCarousel()
    {
        return Yii::$app->RQ->AR(new AdminImageCarouselAR())->all([
            'select' => [
                'sort',
                'img_url',
                'product_url',
                'file_name'
            ],
            'orderBy' => 'sort asc',
        ]);
    }

    public static function getBrand()
    {
        $pid = Yii::$app->RQ->AR(new AdminBrandWallAR())->column([
            'select' => ['id'],
            'where' => 'status = 1',
        ]);


        return Yii::$app->RQ->AR(new AdminBrandWallAR())->all([
            'select' => [
                'pid',
                'img_url',
                'file_name',
                'product_url',
                'sort'
            ],
            'where' => "pid = $pid[0] ",
            'orderBy' => 'sort asc',
        ]);

    }

    public static function getFloor()
    {

        $temp = [];
        $floor = Yii::$app->RQ->AR(new AdminFloorAR())->all([
            'select' => [
                'id',
                'sort',
                'title_ch',
                'title_en',
                'title_simple',
                'floor_color',
                'floor_url'
            ],
            'orderBy' => 'sort asc',
        ]);

        $floorGoods = Yii::$app->RQ->AR(new AdminFloorGoodsAR())->all([
            'select' => [
                'fid',
                'id',
                'good_name',
                'good_url',
                'sale_one',
                'sale_two',
                'good_id',
                'sort',
                'guide_price',
            ],
            'orderBy' => 'sort asc',
        ]);

        $good_ids = array_unique(array_column($floorGoods,'good_id'));

        $goodPrice = Yii::$app->RQ->AR(new ProductSKUAR())->all([
            'select'=>['product_id','max(guidance_price) as guidance_price'],
            'where'=>[
                'product_id'=>$good_ids,
            ],
            'groupBy'=>'product_id',
        ]);

        $goodPrice = self::index2map($goodPrice,'product_id','guidance_price');

        foreach ($floorGoods as $k=>$v)
        {
            $v['price'] = empty(floatval($v['guide_price']))? ($goodPrice[$v['good_id']] ?? 999999999 )  : $v['guide_price'];
            unset($v['guide_price']);
            $temp[$v['fid']][] = array_slice($v, 1);

        }

        foreach ($floor as $k => $v)
        {
            $floor[$k]['child'] = $temp[$v['id']] ?? '';

        }

        return $floor;

    }


}
