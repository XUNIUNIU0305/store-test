<?php
namespace business\modules\site\models;

use Yii;
use common\models\Model;
use business\models\parts\Area;
use common\ActiveRecord\CustomUserRegistercodeAR;
use business\models\handler\CustomUserRegistercodeHandler;
use common\components\handler\Handler;

class CustomModel extends Model{

    const SCE_ADD_REGISTERCODE = 'add_registercode';
    const SCE_GET_REGISTERCODE_LIST = 'get_registercode_list';

    public $area_id;
    public $number;
    public $registered;
    public $current_page;
    public $page_size;

    public function scenarios(){
        return [
            self::SCE_ADD_REGISTERCODE => [
                'area_id',
                'number',
            ],
            self::SCE_GET_REGISTERCODE_LIST => [
                'area_id',
                'registered',
                'current_page',
                'page_size',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['area_id', 'number'],
                'required',
                'message' => 9001,
                'on' => self::SCE_ADD_REGISTERCODE,
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
                ['registered'],
                'default',
                'value' => -1,
            ],
            [
                ['registered'],
                'required',
                'message' => 9001,
            ],
            [
                ['registered'],
                'in',
                'range' => [-1, 0, 1],
                'message' => 13101,
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
                'topArea' => Yii::$app->BusinessUser->account->topArea->level->level == Area::LEVEL_UNDEFINED ? null : Yii::$app->BusinessUser->account->topArea->id,
                'userArea' => Yii::$app->BusinessUser->account->area->id,
                'message' => 13091,
            ],
            [
                ['number'],
                'integer',
                'min' => 1,
                'max' => 100,
                'tooSmall' => 13092,
                'tooBig' => 13092,
                'message' => 13092,
            ],
        ];
    }

    public function addRegistercode(){
        $area = new Area(['id' => $this->area_id]);
        if($area->addCustomRegistercode($this->number)){
            return true;
        }else{
            $this->addError('addRegistercode', 13093);
            return false;
        }
    }

    public function getRegistercodeList(){
        if(is_null($this->area_id)){
            $area = null;
        }else{
            $area = new Area(['id' => $this->area_id]);
        }
        $provider = CustomUserRegistercodeHandler::provide($this->registered, (int)$this->current_page, (int)$this->page_size, $area);
        return Handler::getMultiAttributes($provider, [
            'count',
            'total_count' => 'totalCount',
            'list' => 'models',
            '_func' => [
                'models' => function($list){
                    return array_map(function($registerCode){
                        return Handler::getMultiAttributes($registerCode, [
                            'account',
                            'area' => 'business_area_id',
                            'used',
                            'create_time',
                            'register_time',
                            '_func' => [
                                'register_time' => function($registerTime){
                                    return $registerTime == '0000-01-01 00:00:00' ? '' : $registerTime;
                                },
                                'business_area_id' => function($areaId){
                                    $fullArea = (new Area(['id' => $areaId]))->fullArea;
                                    return array_map(function($area){
                                        return $area->name;
                                    }, $fullArea);
                                },
                            ],
                        ]);
                    }, $list);
                },
            ],
        ]);
    }
}
