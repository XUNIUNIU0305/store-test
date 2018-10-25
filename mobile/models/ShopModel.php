<?php
/**
 * User: JiangYi
 * Date: 2017/5/27
 * Time: 12:49
 * Desc:
 */

namespace mobile\models;


use admin\modules\site\models\WapShopindexModel;
use common\ActiveRecord\SupplyUserAR;
use common\components\handler\Handler;
use common\models\Model;
use common\models\parts\Supplier;
use common\models\parts\supply\SupplyShop;
use yii\helpers\ArrayHelper;

class ShopModel extends Model
{

    const SCE_GET_SUPPLY_INFO='get_supplier_info';
    const SCE_ADV='get_adv';
    const SCE_GET_LIST = 'get_list';
    const SCE_GET_LIST_PRODUCT = 'get_list_product';

    public $id;


    public function scenarios()
    {
        return [
            self::SCE_GET_SUPPLY_INFO=>['id'],
            self::SCE_ADV=>['id'],
            self::SCE_GET_LIST => ['id'],
            self::SCE_GET_LIST_PRODUCT => ['id']
        ];
    }

    public function rules()
    {
        return [
            [
                ['id'],
                'required',
                'message'=>9001,
            ],
            [
                ['id'],
                'exist',
                'targetClass'=>SupplyUserAR::className(),
                'targetAttribute'=>['id'=>'id'],
                'message'=>10005,
            ],
        ];
    }


    public function getSupplierInfo(){
        return Handler::getMultiAttributes(new Supplier(['id'=>$this->id]),[
            'id',
            'store_name'=>'storeName',
            'company_name'=>'companyName',
            'header_img'=>'HeaderImg',
            'brand_name'=>'BrandName',
            'count'=>'GoodsQuantity',
            'province'=>'Province',
            'city'=>'City',
            'district'=>'District',
            'address'=>'Address',
            '_func'=>[
                'Province'=>function($province){
                    if(!$province)return '';
                    return $province->getName();
                },
                'City'=>function($city){
                    if(!$city)return '';
                    return $city->getName();
                },
                'District'=>function($district){
                    if(!$district)return '';
                    return $district->getName();
                }

            ],

        ]);
    }


    /**
     *====================================================
     * 获取商户广告信息
     * @return array
     * @author shuang.li
     *====================================================
     */
    public function getAdv(){
        $shop = new SupplyShop(['id' => $this->id]);
        return ArrayHelper::merge([
            'supply' => [
                'brandName' => $shop->brandName,
                'headerImg' => $shop->headerImg,
                'province' => $shop->getProvince(true)->getName()
            ]
        ], $shop->getShopAdv());
     }

     // 获取主图和轮播图信息
     public function getList()
     {
        $model = new WapShopindexModel(['supply_user_id' => $this->id]);
        if ($list = $model->getList()) {
            return $list;
        } else {
            return [];
        }
     }

     public function getListProduct()
     {
         $model = new WapShopindexModel(['supply_user_id' => $this->id]);
         if ($products = $model->getMobileListProduct()) {
             return $products;
         } else {
            return [];
         }
     }
}
