<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/8/3
 * Time: 上午11:02
 */

namespace mobile\modules\member\models;


use common\components\handler\Handler;
use custom\modules\temp\models\ExchangeModel;
use mobile\models\handler\WaterHandler;
use Yii;

class WaterModel extends ExchangeModel
{
    public $used;//是否使用 0 未使用 1：已使用

    public function scenarios()
    {
        $scenario = [
            self::SCE_GET_LIST=>['page_size','current_page','used'],
        ];
        return array_merge(parent::scenarios(),$scenario);
    }

    public function rules()
    {
        $rule = [
            [
                ['used'],
                'required',
                'message' => 9001,
            ],
            [
                ['used'],
                'in',
                'range'=>[0,1],
                'message'=>10030
            ],
        ];
        return array_merge(parent::rules(),$rule);
    }

    public function getList(){
        $provider = WaterHandler::waterList($this->used,$this->current_page,$this->page_size);
        $area = Yii::$app->CustomUser->CurrentUser->area;
        $tertiaryArea = $area->parent->parent;
        return Handler::getMultiAttributes($provider, [
            'count',
            'total_count' => 'totalCount',
            'list' => 'models',
            '_func' => [
                'models' => function($models)use($tertiaryArea){
                    return array_map(function($model)use($tertiaryArea){
                        return [
                            'pick_id' => $model['pick_id'],
                            'pay_time' => $model['pay_datetime'],
                            'used' => $this->used,
                            'pick_time' => $this->used ? $model['pick_datetime'] : '',
                            'area' => $this->used ? '' : $tertiaryArea->name,
                        ];
                    }, $models);
                },
            ],
        ]);
    }
}