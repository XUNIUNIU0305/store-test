<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/8/20
 * Time: 15:44
 */

namespace mobile\modules\gpubs\models;

use common\models\Model;
use common\ActiveRecord\ActivityGpubsProductAR;
use common\ActiveRecord\ActivityGpubsProductSkuAR;
use common\ActiveRecord\ActivityGpubsGroupAR;
use common\ActiveRecord\CustomUserAR;
use yii\data\ActiveDataProvider;
use common\models\parts\Product;
use common\models\parts\gpubs\GpubsProduct;
use common\models\parts\gpubs\GpubsGroup;

class RecommendModel extends Model
{
    const SCE_GET_HOT_LIST          =   'get_hot_list';
    const SCE_GET_SET_GROUP         =   'get_group';

    const SHOW_ROT_LIST_NUM         =   2;#热点默认显示两条

    public  $product_id;
    public  $page_size;
    public  $cur_page;

    public function scenarios(){
        return [
            self::SCE_GET_HOT_LIST  => [],
            self::SCE_GET_SET_GROUP => ['product_id','page_size','cur_page'],
        ];
    }


    public function rules()
    {
        return [
            [
                ['product_id','page_size','cur_page'],
                'required',
                'message' => 9001,
            ],
            [
                ['product_id','page_size','cur_page'],
                'integer',
                'message' =>9003,
            ],

        ];
    }

    public function getHotList(){
        $rot_recommend = [];
        $count = ActivityGpubsProductAR::find()->select('id')->where(['status'=>GpubsProduct::STATUS_ACTIVE, 'hot_recommend'=>GpubsProduct::HOT_RECOMMENT_IS,])
            ->andWhere(['<', 'activity_start_unixtime', time()])
            ->andWhere(['>', 'activity_end_unixtime', time()])
            ->count();
        if($count == 0)return $rot_recommend;
        $count  = (int)ceil($count/self::SHOW_ROT_LIST_NUM);
        $product = new ActiveDataProvider([
            'query' =>ActivityGpubsProductAR::find()->select([
                'id',
                'product_id',
                'gpubs_type',
                'gpubs_rule_type',
                'min_quantity_per_group',
                'min_member_per_group',
                'min_quantity_per_member_of_group',
                'filename',
            ])->where([
                'status'=>GpubsProduct::STATUS_ACTIVE,
                'hot_recommend'=>GpubsProduct::HOT_RECOMMENT_IS,
            ])->andWhere(['<', 'activity_start_unixtime', time()])
                ->andWhere(['>', 'activity_end_unixtime', time()])
                ->asArray(),
            'pagination' => [
                'page'      => rand(1,$count)-1,
                'pageSize'  => self::SHOW_ROT_LIST_NUM,
            ]
        ]);
        if(!empty($product->models)){
            foreach($product->models as $k=>$item){
                $temp           = [];
                $gpubs_product  = ActivityGpubsProductSkuAR::find()->where(['product_id' => $item['product_id']]);
                $product_obj    = new Product(['id'=>$item['product_id']]);
                if($gpubs_product->sum('stock') == 0)continue;
                $temp['product_id']                      = $item['product_id'];
                $temp['gpubs_type']                      = $item['gpubs_type'];
                $temp['gpubs_rule_type']                 = $item['gpubs_rule_type'];
                $temp['min_quantity_per_group']          = $item['min_quantity_per_group'];
                $temp['min_quanlity_per_member_group']   = $item['min_quantity_per_member_of_group'];
                $temp['min_member_per_group']            = $item['min_member_per_group'];
                $temp['min_price']      = $gpubs_product->min('price');
                $temp['max_price']      = $gpubs_product->max('price');
                $temp['filename']       = $product_obj->mainImage->path;
                $temp['title']          = $product_obj->title;
                $rot_recommend[$k]      = $temp;
            }
        }
        return $rot_recommend;
    }

    public function getGroup(){
        $already_join_group['already_join_num']   = 0;
        $already_join_group['count']              = 0;
        $already_join_group['total_count']        = 0;
        $already_join_group['group']              = [];
        if($activityProduct  = ActivityGpubsProductAR::find()->where(['product_id' => $this->product_id])->one()){
            $product_group = new ActiveDataProvider([
                'query' =>ActivityGpubsGroupAR::find()->select([
                    'id',
                    'status',
                    'custom_user_id',
                    'group_start_unixtime',
                    'group_end_unixtime',
                    'target_quantity',
                    'present_quantity',
                    'target_member',
                    'present_member',
                    'gpubs_rule_type'
                ])->where([
                    'status'    => GpubsGroup::STATUS_WAIT,
                    'gpubs_type'=> GpubsProduct::GPUBS_TYPE_DELIVER,
                    'activity_gpubs_product_id' => $activityProduct->id,
                ])->asArray(),
                'pagination' => [
                    'page'      => $this->cur_page-1,
                    'pageSize'  => $this->page_size,
                ]
            ]);
            $num = ActivityGpubsGroupAR::find()->where([
                'gpubs_type'                => GpubsProduct::GPUBS_TYPE_DELIVER,
                'activity_gpubs_product_id' => $activityProduct->id
            ])->sum('present_member');
            $already_join_group['already_join_num']   = $num ?? 0;
            if( $product_group->count == 0 )return $already_join_group;
            foreach($product_group->models as $k=>$group){
                $temp = [];
                $now_time = time();
                if(($now_time - $group['group_start_unixtime']) > $activityProduct->lifecycle_per_group){
                    continue;
                }
                $temp['surplus_people_num']       = 0;
                $temp['surplus_goods_num']        = 0;
                $temp['group_id']                 = $group['id'];
                $temp['group_start_time']         = $group['group_start_unixtime'];
                $temp['group_end_time']           = $group['group_end_unixtime'];
                $temp['left_unixtime']            = $group['group_end_unixtime'] - $now_time;
                $temp['gpubs_rule_type']          = $group['gpubs_rule_type'];
                if(GpubsProduct::STATUS_GPUBS_RULE_MEMBER == $group['gpubs_rule_type']){
                    $temp['surplus_people_num']   = ($group['target_member']    - $group['present_member']);
                }elseif (GpubsProduct::STATUS_GPUBS_RULE_NUMBER == $group['gpubs_rule_type']){
                    $temp['surplus_goods_num']    = ($group['target_quantity']  - $group['present_quantity']);
                }else{
                    $temp['surplus_people_num']   = ($group['target_member']    - $group['present_member']);
                }
                $custom_user = CustomUserAR::findOne($group['custom_user_id']);
                $temp['account']                  = $custom_user->account;
                $temp['header_img']               = $custom_user->header_img;
                $already_join_group['group'][$k]  = $temp;
            }
            $already_join_group['group']              = array_values($already_join_group['group']);
            $already_join_group['count']              = count($already_join_group['group']);
            $already_join_group['total_count']        = $product_group->totalCount;
        }
        return $already_join_group;
    }

}