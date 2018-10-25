<?php
/**
 * User: JiangYi
 * Date: 2017/5/27
 * Time: 12:49
 * Desc:
 */

namespace wechat\models;


use common\ActiveRecord\SupplyUserAR;
use common\components\handler\Handler;
use common\models\Model;
use common\models\parts\Supplier;

class ShopModel extends Model
{

    const SCE_GET_SUPPLY_INFO='get_supplier_info';

    public $id;


    public function scenarios()
    {
        return [
            self::SCE_GET_SUPPLY_INFO=>['id'],
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
            'account',
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

}