<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/2
 * Time: 11:11
 */

namespace admin\modules\site\models;


use common\components\handler\Handler;
use common\components\handler\supply\SupplyUserHandler;
use common\models\Model;
use common\models\parts\district\City;
use common\models\parts\district\District;
use common\models\parts\district\Province;
use common\models\parts\supply\SupplyUser;

class SupplyModel extends Model
{


    const SCE_GET_SUPPLY_LIST="get_supply_list";




    public $page_size;
    public $current_page;

    public function scenarios()
    {
        return [
            self::SCE_GET_SUPPLY_LIST=>['page_size','current_page'],
        ];
    }

    public function rules()
    {
        return [
            [
                ['page_size'],
                'default',
                'value'=>10,
            ],
            [
                ['current_page'],
                'default',
                'value'=>1,
            ],
            [
                ['page_size','current_page'],
                'required',
                'message'=>9001,
            ]
        ];
    }

    public function getSupplyList(){
        $model=SupplyUserHandler::search($this->page_size,$this->current_page);
        $data=array_map(function($item){
            return Handler::getMultiAttributes(new SupplyUser(['id'=>$item['id']]),[
                'id',
                'account'=>'Account',
                'company_name'=>'CompanyName',
                'store_name'=>'StoreName',
                'brand_name'=>'BrandName',
                'header_img'=>'HeaderImg',
                'real_name'=>'RealName',
                'mobile',
                'area_code'=>'AreaCode',
                'telephone'=>'Telephone',
                'province',
                'city',
                'district',
                'address',
                '_func'=>[
                    'province'=>function($item){
                        if($item){
                            $item=new Province(['provinceId'=>$item]);
                            return $item?$item->getName():"";
                        }
                        return '';

                    },
                    'city'=>function($item){
                        if($item){
                            $item=new City(['cityId'=>$item]);
                            return $item?$item->getName():"";
                        }
                       return '';
                    },
                    'district'=>function($item){
                        if($item){
                            $item=new District(['districtId'=>$item]);
                            return $item?$item->getName():"";
                        }
                        return '';

                    }

                ],
            ]);
        },$model->models);


        return [
            'count' => $model->count,
            'total_count' => $model->totalCount,
            'codes' => $data,
        ];

    }

}