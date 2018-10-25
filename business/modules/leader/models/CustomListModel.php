<?php
namespace business\modules\leader\models;

use Yii;
use common\models\Model;
use business\models\handler\CustomUserHandler;
use common\components\handler\Handler;
use business\models\parts\Area;

class CustomListModel extends Model{

    const SCE_GET_CUSTOM_LIST = 'get_custom_list';

    public $account;
    public $area_id;
    public $current_page;
    public $page_size;

    public function scenarios(){
        return [
            self::SCE_GET_CUSTOM_LIST => [
                'account',
                'area_id',
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
                ['account'],
                'integer',
                'min' => 100000000,
                'max' => 999999999,
                'tooSmall' => 9002,
                'tooBig' => 9002,
                'message' => 9002,
            ],
            [
                ['current_page', 'page_size'],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
            [
                ['area_id'],
                'business\validators\AreaValidator',
                'hasChild' => false,
                'topArea' => ($topArea = Yii::$app->BusinessUser->account->topArea)->level->level == Area::LEVEL_UNDEFINED ? null : $topArea->id,
                'userArea' => Yii::$app->BusinessUser->account->area->id,
                'message' => 13121,
            ],
        ];
    }

    public function getCustomList(){
        if(is_null($this->area_id) && empty($this->account)){
            $this->addError('getCustomList', 9001);
            return false;
        }
        $area = is_null($this->area_id) ? null : new Area(['id' => $this->area_id]);
        $provider = CustomUserHandler::provide($this->account, $area, (int)$this->current_page, (int)$this->page_size);
        return Handler::getMultiAttributes($provider, [
            'count',
            'total_count' => 'totalCount',
            'list' => 'models',
            '_func' => [
                'models' => function($list){
                    if(!is_null($this->account) && !empty($list)){
                        $userTopArea = Yii::$app->BusinessUser->account->topArea;
                        if($userTopArea->level->level != Area::LEVEL_UNDEFINED){
                            $account = current($list);
                            $accountTopArea = (new Area(['id' => $account['business_area_id']]))->topArea;
                            if($userTopArea->id != $accountTopArea->id)return [];
                        }
                    }
                    return array_map(function($one){
                        $area = new Area(['id' => $one['business_area_id']]);
                        return [
                            'account' => $one['account'],
                            'shop_name' => $one['shop_name'],
                            'nick_name' => $one['nick_name'],
                            'area' => array_map(function($oneArea){
                                return Handler::getMultiAttributes($oneArea, [
                                    'id',
                                    'name',
                                ]);
                            }, $area->fullArea),
                            'leader' => ($account = $area->leader) ? $account->name : '',
                            'commissar' => ($account = $area->commissar) ? $account->name : '',
                        ];
                    }, $list);
                },
            ],
        ]);
    }
}
