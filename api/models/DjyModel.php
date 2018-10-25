<?php
namespace api\models;

use common\models\Model;
use common\models\temp\djy\Djy;
use common\models\temp\djy\GlobalStatistics;
use common\models\temp\djy\TopAreaStatistics;
use common\models\temp\djy\QuaternaryAreaStatistics;
use common\models\temp\djy\OrderStatistics;
use business\models\parts\Area;
use common\components\handler\Handler;
use business\models\parts\AreaLevel;

class DjyModel extends Model{

    public $top_area_id;
    public $secondary_area_id;
    public $tertiary_area_id;
    public $quaternary_area_id;
    public $current_page;
    public $page_size;
    public $account;
    public $parent_area_id;
    private static $_djy;

    const SCE_GET_TOTAL_FEE = 'get_total_fee';
    const SCE_GET_TOTAL_SKU = 'get_total_sku';
    const SCE_GET_TOP_AREA_FEE_LIST = 'get_top_area_fee_list';
    const SCE_GET_TOP_AREA_FEE = 'get_top_area_fee';
    const SCE_GET_TOP_AREA_SKU = 'get_top_area_sku';
    const SCE_GET_QUATERNARY_AREA_FEE_LIST = 'get_quaternary_area_fee_list';
    const SCE_GET_QUATERNARY_AREA_FEE = 'get_quaternary_area_fee';
    const SCE_GET_QUATERNARY_AREA_SKU = 'get_quaternary_area_sku';
    const SCE_GET_STORE_FEE_LIST = 'get_store_fee_list';
    const SCE_GET_COMMANDER = 'get_commander';
    const SCE_GET_ORDER_LIST = 'get_order_list';
    const SCE_GET_AREA_LIST = 'get_area_list';

    public function init(){
        parent::init();
        self::$_djy = new Djy;
    }

    public function scenarios(){
        return [
            self::SCE_GET_TOTAL_FEE => [],
            self::SCE_GET_TOTAL_SKU => [],
            self::SCE_GET_TOP_AREA_FEE_LIST => [],
            self::SCE_GET_TOP_AREA_FEE => [
                'top_area_id',
            ],
            self::SCE_GET_TOP_AREA_SKU => [
                'top_area_id',
            ],
            self::SCE_GET_QUATERNARY_AREA_FEE_LIST => [
                'top_area_id',
            ],
            self::SCE_GET_QUATERNARY_AREA_FEE => [
                'quaternary_area_id',
            ],
            self::SCE_GET_QUATERNARY_AREA_SKU => [
                'quaternary_area_id',
            ],
            self::SCE_GET_STORE_FEE_LIST => [
                'quaternary_area_id',
                'current_page',
                'page_size',
            ],
            self::SCE_GET_COMMANDER => [
                'quaternary_area_id',
            ],
            self::SCE_GET_ORDER_LIST => [
                'top_area_id',
                'secondary_area_id',
                'tertiary_area_id',
                'quaternary_area_id',
                'account',
                'current_page',
                'page_size',
            ],
            self::SCE_GET_AREA_LIST => [
                'parent_area_id',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['parent_area_id'],
                'default',
                'value' => 0,
            ],
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
                ['top_area_id', 'quaternary_area_id', 'current_page', 'page_size', 'parent_area_id'],
                'required',
                'message' => 9001,
                'except' => [self::SCE_GET_ORDER_LIST],
            ],
        ];
    }

    public function getAreaList(){
        $area = new Area([
            'id' => $this->parent_area_id,
        ]);
        return Handler::getMultiAttributes($area, [
            'level' => 'level',
            'has_child' => 'level',
            'list' => 'children',
            '_func' => [
                'level' => function($level, $callbackName){
                    if($callbackName == 'level'){
                        return $level->childLevel;
                    }else{
                        if(!$childLevel = $level->childLevel)return false;
                        try{
                            (new AreaLevel(['level' => $childLevel + 1]));
                            return true;
                        }catch(\Exception $e){
                            return false;
                        }
                    }
                },
                'children' => function($areaList){
                    return array_map(function($area){
                        return Handler::getMultiAttributes($area, [
                            'id',
                            'name',
                        ]);
                    }, $areaList);
                }
            ],
        ]);
    }

    public function getOrderList(){
        $order = new OrderStatistics([
            'djy' => self::$_djy,
        ]);
        return $order->achieveList([
            'topAreaId' => $this->top_area_id,
            'secondaryAreaId' => $this->secondary_area_id,
            'tertiaryAreaId' => $this->tertiary_area_id,
            'quaternaryAreaId' => $this->quaternary_area_id,
            'account' => $this->account,
        ], $this->current_page, $this->page_size);
    }

    public function getCommander(){
        $quaternaryArea = new QuaternaryAreaStatistics([
            'djy' => self::$_djy,
        ]);
        return $quaternaryArea->getCommander($this->quaternary_area_id);
    }

    public function getStoreFeeList(){
        $quaternaryArea = new QuaternaryAreaStatistics([
            'djy' => self::$_djy,
        ]);
        return $quaternaryArea->getList($this->quaternary_area_id, $this->current_page, $this->page_size);
    }

    public function getQuaternaryAreaSku(){
        $quaternaryArea = new QuaternaryAreaStatistics([
            'djy' => self::$_djy,
        ]);
        return $quaternaryArea->getSku($this->quaternary_area_id);
    }

    public function getQuaternaryAreaFee(){
        $quaternaryArea = new QuaternaryAreaStatistics([
            'djy' => self::$_djy,
        ]);
        return [
            'total_fee' => $quaternaryArea->getTotal($this->quaternary_area_id) ? : 0,
        ];
    }

    public function getQuaternaryAreaFeeList(){
        $topArea = new TopAreaStatistics([
            'djy' => self::$_djy,
        ]);
        return $topArea->getList($this->top_area_id);
    }

    public function getTopAreaSku(){
        $topArea = new TopAreaStatistics([
            'djy' => self::$_djy,
        ]);
        return $topArea->getSku($this->top_area_id);
    }

    public function getTopAreaFee(){
        $topArea = new TopAreaStatistics([
            'djy' => self::$_djy,
        ]);
        return [
            'total_fee' => $topArea->getTotal($this->top_area_id) ? : 0,
        ];
    }

    public function getTotalFee(){
        $global = new GlobalStatistics([
            'djy' => self::$_djy,
        ]);
        return $global->getTotal();
    }

    public function getTotalSku(){
        $global = new GlobalStatistics([
            'djy' => self::$_djy,
        ]);
        return $global->getSku();
    }

    public function getTopAreaFeeList(){
        $global = new GlobalStatistics([
            'djy' => self::$_djy,
        ]);
        return $global->getTopAreaTotal();
    }
}
