<?php
namespace admin\modules\site\models;

use Yii;
use common\models\Model;
use admin\components\handler\RegisterCodeHandler;

class RegistercodeModel extends Model{

    const SCE_CREATE_CUSTOM_CODE = 'create_custom_code';
    const SCE_GET_CUSTOM_CODE = 'get_custom_code';

    public $quantity;
    public $current_page;
    public $page_size;

    public function scenarios(){
        return [
            self::SCE_CREATE_CUSTOM_CODE => [
                'quantity',
            ],
            self::SCE_GET_CUSTOM_CODE => [
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
                ['quantity', 'current_page', 'page_size'],
                'required',
                'message' => 9001,
            ],
            [
                ['quantity'],
                'integer',
                'min' => 1,
                'max' => 100,
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
        ];
    }

    public function getCustomCode(){
        $codesProvider = RegisterCodeHandler::provideCustomCodes(false, $this->current_page, $this->page_size);
        return [
            'count' => $codesProvider->count,
            'total_count' => $codesProvider->totalCount,
            'codes' => array_map(function($code){
                return RegisterCodeHandler::getMultiAttributes($code, [
                    'account',
                    'used',
                    'create_time',
                    'register_time',
                    '_func' => [
                        'register_time' => function($time){
                            return $time == '0000-01-01 00:00:00' ? '' : $time;
                        },
                    ],
                ]);
            }, $codesProvider->models),
        ];
    }

    public function createCustomCode(){
        if(RegisterCodeHandler::createCustomCode($this->quantity, false)){
            return true;
        }else{
            $this->addError('createCustomCode', 5081);
            return false;
        }
    }
}
