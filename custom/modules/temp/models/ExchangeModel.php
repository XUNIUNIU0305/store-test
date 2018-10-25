<?php
namespace custom\modules\temp\models;

use Yii;
use common\models\Model;
use common\components\handler\Handler;
use custom\models\parts\temp\OrderLimit\activity\ApiForCustom;

class ExchangeModel extends Model{

    const SCE_GET_LIST = 'get_list';

    public $current_page;
    public $page_size;

    public function scenarios(){
        return [
            self::SCE_GET_LIST => [
                'current_page',
                'page_size',
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
                ['current_page', 'page_size'],
                'required',
                'message' => 9001,
            ],
            [
                ['current_page', 'page_size'],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
        ];
    }

    public function getList(){
        $provider = ApiForCustom::provideList(Yii::$app->user->id, (int)$this->current_page, (int)$this->page_size);
        $area = Yii::$app->CustomUser->CurrentUser->area;
        $quaternaryArea = $area->parent;
        return Handler::getMultiAttributes($provider, [
            'count',
            'total_count' => 'totalCount',
            'list' => 'models',
            '_func' => [
                'models' => function($models)use($quaternaryArea){
                    return array_map(function($model)use($quaternaryArea){
                        $used = $model['business_area_id'] ? true : false;
                        return [
                            'pick_id' => $model['pick_id'],
                            'pay_time' => $model['pay_datetime'],
                            'used' => $used,
                            'pick_time' => $used ? $model['pick_datetime'] : '',
                            'area' => $used ? '' : $quaternaryArea->name,
                        ];
                    }, $models);
                },
            ],
        ]);
    }
}
