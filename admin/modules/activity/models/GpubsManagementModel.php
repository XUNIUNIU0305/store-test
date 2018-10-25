<?php
namespace admin\modules\activity\models;

use common\models\parts\gpubs\GpubsProduct;
use Yii;
use common\models\Model;
use common\ActiveRecord\ActivityGpubsGroupDetailAR;
use common\ActiveRecord\ActivityGpubsGroupAR;
use common\ActiveRecord\DistrictProvinceAR;
use common\ActiveRecord\DistrictCityAR;
use common\ActiveRecord\CustomUserAR;
use common\ActiveRecord\BusinessAreaAR;
use business\models\parts\Area;
use common\ActiveRecord\ActivityGpubsProductAR;
use common\models\parts\gpubs\GpubsGroup;
use common\models\parts\gpubs\GpubsGroupDetail;
use yii\data\ActiveDataProvider;
use common\components\handler\Handler;

class GpubsManagementModel extends Model{

    const STATUS_ALL = -1;
    const STATUS_FORCE_ESTABLISH = -2;

    const TYPE_ALL = -1;
    const TYPE_GPUBS = 1;
    const TYPE_DELIVER = 2;
    const TYPE_UNKNOWN = 3;

    const AREA_ALL = -1;


    const SCE_GET_STATISTICS = 'get_statistics';
    const SCE_GET_LIST = 'get_list';
    const SCE_FORCE_ESTABLISH = 'force_establish';
    const SCE_GET_SECONDARY_AREAS = 'get_secondary_areas';
    const SCE_GET_TERTIARY_AREAS = 'get_tertiary_areas';
    const SCE_GET_QUATERNARY_AREAS = 'get_quaternary_areas';

    private $_group;

    public $type;
    public $top_area_id;
    public $secondary_area_id;
    public $tertiary_area_id;
    public $quaternary_area_id;
    public $status;
    public $current_page;
    public $page_size;
    public $group_id;
    public $activity_gpubs_id;//搜索 活动编号

    public function scenarios(){
        return [
            self::SCE_GET_STATISTICS => [
                'type',
                'top_area_id',
                'secondary_area_id',
                'tertiary_area_id',
                'quaternary_area_id',
                'status',
                'activity_gpubs_id'
            ],
            self::SCE_GET_LIST => [
                'type',
                'top_area_id',
                'secondary_area_id',
                'tertiary_area_id',
                'quaternary_area_id',
                'status',
                'current_page',
                'page_size',
                'activity_gpubs_id'
            ],
            self::SCE_FORCE_ESTABLISH => [
                'group_id',
            ],
            self::SCE_GET_SECONDARY_AREAS => [
                'top_area_id',
            ],
            self::SCE_GET_TERTIARY_AREAS => [
                'secondary_area_id',
            ],
            self::SCE_GET_QUATERNARY_AREAS => [
                'tertiary_area_id',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['current_page'],
                'default',
                'value' => 1,
            ],
            [
                ['page_size'],
                'default',
                'value' => 10,
            ],
            [
                ['type', 'top_area_id', 'secondary_area_id', 'tertiary_area_id', 'quaternary_area_id', 'status', 'current_page', 'page_size', 'group_id'],
                'required',
                'message' => 9001,
            ],
            [
                ['type'],
                'in',
                'range' => static::getType(),
                'message' => 9002,
            ],
            [
                ['status'],
                'in',
                'range' => static::getGpubsGroupStatuses(),
                'message' => 9002,
            ],
            [
                ['top_area_id', 'secondary_area_id', 'tertiary_area_id', 'quaternary_area_id'],
                function($attr){
                    if($this->$attr == self::AREA_ALL)return;
                    switch($attr){
                        case 'top_area_id':
                            $level = Area::LEVEL_TOP;
                            break;

                        case 'secondary_area_id':
                            $level = Area::LEVEL_SECONDARY;
                            break;

                        case 'tertiary_area_id':
                            $level = Area::LEVEL_TERTIARY;
                            break;

                        case 'quaternary_area_id':
                            $level = Area::LEVEL_QUATERNARY;
                            break;

                        default:
                            $level = false;
                            break;
                    }
                    if($areaAR = BusinessAreaAR::findOne($this->$attr)){
                        if($areaAR->level == $level && $areaAR->display == Area::DISPLAY_ON){
                            return;
                        }else{
                            $this->addError($attr, 9002);
                        }
                    }else{
                        $this->addError($attr, 9002);
                    }
                },
            ],
            [
                ['current_page', 'page_size'],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
            [
                ['group_id'],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
            [
                ['group_id'],
                function($attr){
                    try{
                        $this->_group = new GpubsGroup([
                            'id' => $this->$attr,
                        ]);
                        if($this->_group->status != GpubsGroup::STATUS_WAIT){
                            $this->addError($attr, 5611);
                        }
                    }catch(\Exception $e){
                        $this->addError($attr, 9002);
                    }
                }
            ],
            [
                ['activity_gpubs_id'],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
        ];
    }

    public static function getGpubsGroupStatuses(){
        return [
            self::STATUS_ALL,
            self::STATUS_FORCE_ESTABLISH,
            GpubsGroup::STATUS_WAIT,
            GpubsGroup::STATUS_ESTABLISH,
            GpubsGroup::STATUS_CANCELED,
        ];
    }

    public static function getType(){
        return [
            self::TYPE_ALL,
            self::TYPE_GPUBS,
            self::TYPE_DELIVER,
            self::TYPE_UNKNOWN,
        ];
    }

    public static function getTopAreas(){
        return BusinessAreaAR::find()->
            select(['id', 'name'])->
            where([
                'parent_business_area_id' => Area::LEVEL_UNDEFINED,
                'display' => Area::DISPLAY_ON,
            ])->
            all();
    }

    public function getSecondaryAreas(){
        return BusinessAreaAR::find()->
            select(['id', 'name'])->
            where([
                'parent_business_area_id' => $this->top_area_id,
                'display' => Area::DISPLAY_ON,
            ])->
            all();
    }

    public function getTertiaryAreas(){
        return BusinessAreaAR::find()->
            select(['id', 'name'])->
            where([
                'parent_business_area_id' => $this->secondary_area_id,
                'display' => Area::DISPLAY_ON,
            ])->
            all();
    }

    public function getQuaternaryAreas(){
        return BusinessAreaAR::find()->
            select(['id', 'name'])->
            where([
                'parent_business_area_id' => $this->tertiary_area_id,
                'display' => Area::DISPLAY_ON,
            ])->
            all();
    }

    
    protected function getOwnerIds(){
        $topArea = $this->top_area_id == self::AREA_ALL ? null : $this->top_area_id;
        $secondaryArea = $this->secondary_area_id == self::AREA_ALL ? null : $this->secondary_area_id;
        $tertiaryArea = $this->tertiary_area_id == self::AREA_ALL ? null : $this->tertiary_area_id;
        $quaternaryArea = $this->quaternary_area_id == self::AREA_ALL ? null : $this->quaternary_area_id;
        $targetCustomUserId = CustomUserAR::find()->
            select(['id'])->
            filterWhere([
                'business_top_area_id' => $topArea,
                'business_secondary_area_id' => $secondaryArea,
                'business_tertiary_area_id' => $tertiaryArea,
                'business_quaternary_area_id' => $quaternaryArea,
            ])->
            column();
        $allGroupUserId = ActivityGpubsGroupAR::find()->
            select(['custom_user_id'])->
            where([
                'status' => [
                    GpubsGroup::STATUS_WAIT,
                    GpubsGroup::STATUS_CANCELED,
                    GpubsGroup::STATUS_ESTABLISH,
                    GpubsGroup::STATUS_DELIVERED,
                ],
            ])->
            distinct()->
            asArray()->
            column();
        return array_values(array_intersect($targetCustomUserId, $allGroupUserId));
    }

    public function getStatistics(){
        if($this->type == self::TYPE_UNKNOWN){
            return [
                'total_quantity' => 0,
                'wait_quantity' => 0,
                'establish_quantity' => 0,
                'establish_product_quantity' => 0,
                'establish_total_fee' => 0,
                'wait_total_fee' => 0,
            ];
        }
        $status = $this->status == self::STATUS_ALL ? [
            GpubsGroup::STATUS_WAIT,
            GpubsGroup::STATUS_ESTABLISH,
            GpubsGroup::STATUS_CANCELED,
            GpubsGroup::STATUS_DELIVERED,
        ] : $this->status;
        $ownerIds = $this->getOwnerIds() ? : -1;
        if($status == self::STATUS_FORCE_ESTABLISH){
            return [
                'total_quantity' => ActivityGpubsGroupAR::find()->
                    filterWhere([
                        'custom_user_id' => $ownerIds,
                        'status' => [
                            GpubsGroup::STATUS_WAIT,
                            GpubsGroup::STATUS_ESTABLISH,
                            GpubsGroup::STATUS_CANCELED,
                            GpubsGroup::STATUS_DELIVERED,
                        ],
                        'gpubs_type' => $this->type == self::TYPE_ALL ? '':$this->type,
                        'activity_gpubs_product_id'=> $this->activity_gpubs_id,
                    ])->
                    count(),
                'wait_quantity' => 0,
                'establish_quantity' => ActivityGpubsGroupAR::find()->
                    filterWhere([
                        'custom_user_id' => $ownerIds,
                        'gpubs_type' => $this->type == self::TYPE_ALL ? '':$this->type,
                        'activity_gpubs_product_id'=> $this->activity_gpubs_id,
                    ])->
                    andWhere([
                        'status' => [
                            GpubsGroup::STATUS_ESTABLISH,
                            GpubsGroup::STATUS_DELIVERED,
                        ],
                    ])->
                    andWhere(['or', '[[target_quantity]] > [[present_quantity]]', '[[target_member]] > [[present_member]]'])->
                    count(),
                'establish_product_quantity' => ActivityGpubsGroupAR::find()->
                    filterWhere([
                        'custom_user_id' => $ownerIds,
                        'gpubs_type' => $this->type == self::TYPE_ALL ? '':$this->type,
                        'activity_gpubs_product_id'=> $this->activity_gpubs_id,
                    ])->
                    andWhere([
                        'status' => [
                            GpubsGroup::STATUS_ESTABLISH,
                            GpubsGroup::STATUS_DELIVERED,
                        ],
                    ])->
                    andWhere(['or', '[[target_quantity]] > [[present_quantity]]', '[[target_member]] > [[present_member]]'])->
                    sum('present_quantity') ? : 0,
                'establish_total_fee' => ActivityGpubsGroupAR::find()->
                    filterWhere([
                        'custom_user_id' => $ownerIds,
                        'gpubs_type' => $this->type == self::TYPE_ALL ? '':$this->type,
                        'activity_gpubs_product_id'=> $this->activity_gpubs_id,
                    ])->
                    andWhere([
                        'status' => [
                            GpubsGroup::STATUS_ESTABLISH,
                            GpubsGroup::STATUS_DELIVERED,
                        ],
                    ])->
                    andWhere(['or', '[[target_quantity]] > [[present_quantity]]', '[[target_member]] > [[present_member]]'])->
                    sum('total_fee') ? : 0,
                'wait_total_fee' => 0,
            ];
        }else{
            return [
                'total_quantity' => ActivityGpubsGroupAR::find()->
                    filterWhere([
                        'custom_user_id' => $ownerIds,
                        'status' => [
                            GpubsGroup::STATUS_WAIT,
                            GpubsGroup::STATUS_ESTABLISH,
                            GpubsGroup::STATUS_CANCELED,
                            GpubsGroup::STATUS_DELIVERED,
                        ],
                        'gpubs_type' => $this->type == self::TYPE_ALL ? '':$this->type,
                        'activity_gpubs_product_id'=> $this->activity_gpubs_id,
                    ])->
                    count(),
                'wait_quantity' => ($status == GpubsGroup::STATUS_WAIT || is_array($status)) ? ActivityGpubsGroupAR::find()->
                    filterWhere([
                        'custom_user_id' => $ownerIds,
                        'gpubs_type' => $this->type == self::TYPE_ALL ? '':$this->type,
                        'activity_gpubs_product_id'=> $this->activity_gpubs_id,
                    ])->
                    andWhere([
                        'status' => GpubsGroup::STATUS_WAIT,
                    ])->
                    count() : 0,
                'establish_quantity' => ($status == GpubsGroup::STATUS_ESTABLISH || is_array($status)) ? ActivityGpubsGroupAR::find()->
                    filterWhere([
                        'custom_user_id' => $ownerIds,
                        'gpubs_type' => $this->type == self::TYPE_ALL ? '':$this->type,
                        'activity_gpubs_product_id'=> $this->activity_gpubs_id,
                    ])->
                    andWhere([
                        'status' => [
                            GpubsGroup::STATUS_ESTABLISH,
                            GpubsGroup::STATUS_DELIVERED,
                        ],
                    ])->
                    count() : 0,
                'establish_product_quantity' => ($status == GpubsGroup::STATUS_ESTABLISH || is_array($status)) ? (ActivityGpubsGroupAR::find()->
                    filterWhere([
                        'custom_user_id' => $ownerIds,
                        'gpubs_type' => $this->type == self::TYPE_ALL ? '':$this->type,
                        'activity_gpubs_product_id'=> $this->activity_gpubs_id,
                    ])->
                    andWhere([
                        'status' => [
                            GpubsGroup::STATUS_ESTABLISH,
                            GpubsGroup::STATUS_DELIVERED,
                        ],
                    ])->
                    sum('present_quantity') ? : 0) : 0,
                'establish_total_fee' => ($status == GpubsGroup::STATUS_ESTABLISH || is_array($status)) ? (ActivityGpubsGroupAR::find()->
                    filterWhere([
                        'custom_user_id' => $ownerIds,
                        'gpubs_type' => $this->type == self::TYPE_ALL ? '':$this->type,
                        'activity_gpubs_product_id'=> $this->activity_gpubs_id,
                    ])->
                    andWhere([
                        'status' => [
                            GpubsGroup::STATUS_ESTABLISH,
                            GpubsGroup::STATUS_DELIVERED,
                        ],
                    ])->
                    sum('total_fee') ? : 0) : 0,
                'wait_total_fee' => ($status == GpubsGroup::STATUS_WAIT || is_array($status)) ? ActivityGpubsGroupDetailAR::find()->
                    filterWhere([
                        'own_user_id' => $ownerIds,
                        'gpubs_type' => $this->type == self::TYPE_ALL ? '':$this->type,
                        'activity_gpubs_product_id'=> $this->activity_gpubs_id,
                    ])->
                    andWhere([
                        'status' => GpubsGroupDetail::STATUS_WAIT,
                    ])->
                    sum('total_fee') : 0,
            ];
        }
    }

    public function getList(){
        if($this->type == self::TYPE_UNKNOWN){
            return [
                'count' => 0,
                'total_count' => 0,
                'data' => [],
            ];
        }
        $status = $this->status == self::STATUS_ALL ? [
            GpubsGroup::STATUS_WAIT,
            GpubsGroup::STATUS_ESTABLISH,
            GpubsGroup::STATUS_CANCELED,
            GpubsGroup::STATUS_DELIVERED,
        ] : ($this->status == GpubsGroup::STATUS_ESTABLISH ? [
            GpubsGroup::STATUS_ESTABLISH,
            GpubsGroup::STATUS_DELIVERED,
        ] : $this->status);
        $ownerIds = $this->getOwnerIds() ? : -1;
        if($this->status == self::STATUS_FORCE_ESTABLISH){
            $sqlQuery = ActivityGpubsGroupAR::find()->
                select(['id'])->
                filterWhere([
                    'custom_user_id' => $ownerIds,
                    'gpubs_type' => $this->type == self::TYPE_ALL ? '':$this->type,
                    'activity_gpubs_product_id'=> $this->activity_gpubs_id,
                ])->
                andWhere([
                    'status' => [
                        GpubsGroup::STATUS_ESTABLISH,
                        GpubsGroup::STATUS_DELIVERED,
                    ],
                ])->
                andWhere(['or', '[[target_quantity]] > [[present_quantity]]', '[[target_member]] > [[present_member]]'])->
                asArray();
        }else{
            $sqlQuery = ActivityGpubsGroupAR::find()->
            select(['id'])->
            filterWhere([
                'custom_user_id' => $ownerIds,
                'status' => $status,
                'gpubs_type' => $this->type == self::TYPE_ALL ? '':$this->type,
                'activity_gpubs_product_id'=> $this->activity_gpubs_id,
            ])->
            asArray();

        }
        $provider = new ActiveDataProvider([
            'query' => $sqlQuery,
            'pagination' => [
                'page' => $this->current_page - 1,
                'pageSize' => $this->page_size,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);
        return Handler::getMultiAttributes($provider, [
            'count',
            'total_count' => 'totalCount',
            'data' => 'models',
            '_func' => [
                'models' => function($models){
                    return array_map(function($model){
                        $group = new GpubsGroup($model);
                        return Handler::getMultiAttributes($group, [
                            'id',
                            'group_number' => 'groupNumber',
                            'type' => 'id',
                            'product_title' => 'gpubsProduct',
                            'owner_account' => 'custom_user_id',
                            'owner_name' => 'consignee',
                            'area' => 'custom_user_id',
                            'group_start_datetime',
                            'group_end_datetime',
                            'activity_end_datetime' => 'activity_gpubs_product_id',
                            'gpubs_type',
                            'present_quantity',
                            'target_quantity',
                            'target_member',
                            'present_member',
                            'gpubs_rule_type',
                            'min_quanlity_per_member_of_group' => 'min_quantity_per_member_of_group',
                            'activity_gpubs_id' => 'activity_gpubs_product_id',
                            'total_fee' => 'id',
                            'status',
                            '_func' => [
                                'id' => function($id, $alias){
                                    if($alias == 'type'){
                                        return self::TYPE_GPUBS;
                                    }elseif($alias == 'total_fee'){
                                        return ActivityGpubsGroupDetailAR::find()->
                                            where([
                                                'activity_gpubs_group_id' => $id,
                                            ])->
                                            sum('total_fee') ? : 0;
                                    }else{
                                        return $id;
                                    }
                                },
                                'gpubsProduct' => function($gpubsProduct){
                                    return $gpubsProduct->product->title;
                                },
                                'custom_user_id' => function($id, $alias){
                                    if($alias == 'owner_account'){
                                        return CustomUserAR::find()->
                                            select(['account'])->
                                            where(['id' => $id])->
                                            scalar();
                                    }else{
                                        $areas = CustomUserAR::find()->
                                            select([
                                                'business_top_area_id',
                                                'business_secondary_area_id',
                                                'business_tertiary_area_id',
                                                'business_quaternary_area_id',
                                            ])->
                                            where([
                                                'id' => $id,
                                            ])->
                                            asArray()->
                                            one();
                                        return array_map(function($areaId){
                                            return BusinessAreaAR::find()->
                                                select(['level', 'name'])->
                                                where([
                                                    'id' => $areaId,
                                                ])->
                                                asArray()->
                                                one();
                                        }, $areas);
                                    }
                                },
                                'activity_gpubs_product_id' => function($id ,$alias){
                                    if ($alias == 'activity_gpubs_id'){
                                        return $id;
                                    }else{
                                        return ActivityGpubsProductAR::find()->
                                        select(['activity_end_datetime'])->
                                        where(['id' => $id])->
                                        scalar();
                                    }
                                },
                            ],
                        ]);
                    }, $models);
                },
            ],
        ]);
    }

    public function forceEstablish(){
        if($this->_group->setEstablished(false)){
            return true;
        }else{
            $this->addError('forceEstablish', 5612);
            return false;
        }
    }
}
