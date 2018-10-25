<?php
namespace business\modules\temp\models;

use Yii;
use common\models\Model;
use common\components\handler\Handler;
use common\models\parts\custom\CustomUser;
use business\models\parts\Area;
use custom\models\parts\temp\OrderLimit\activity\ApiForBusiness;

class ListModel extends Model{

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
        if(Yii::$app->BusinessUser->account->area->level->level != Area::LEVEL_QUATERNARY){
            $this->addError('exchangeQuery', 13251);
            return false;
        }
        $provider = ApiForBusiness::provideList(Yii::$app->BusinessUser->account->area->id, (int)$this->current_page, (int)$this->page_size);
        return Handler::getMultiAttributes($provider, [
            'count',
            'total_count' => 'totalCount',
            'list' => 'models',
            '_func' => [
                'models' => function($models){
                    return array_map(function($model){
                        return [
                            'pick_id' => $model['pick_id'],
                            'pay_time' => $model['pay_datetime'],
                            'pick_time' => $model['pick_datetime'],
                            'custom_user' => (new CustomUser(['id' => $model['custom_user_id']]))->account,
                        ];
                    }, $models);
                },
            ],
        ]);
    }
}
