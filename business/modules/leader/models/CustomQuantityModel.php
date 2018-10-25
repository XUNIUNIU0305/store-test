<?php
namespace business\modules\leader\models;

use Yii;
use common\models\Model;
use business\validators\AreaValidator;
use business\models\parts\Area;
use common\components\handler\Handler;

class CustomQuantityModel extends Model{

    const SCE_GET_CUSTOM_QUANTITY = 'get_custom_quantity';

    public $parent_id;

    public function scenarios(){
        return [
            self::SCE_GET_CUSTOM_QUANTITY => [
                'parent_id',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['parent_id'],
                'required',
                'message' => 9001,
            ],
            [
                ['parent_id'],
                'business\validators\AreaValidator',
                'userArea' => $this->parent_id == Area::LEVEL_UNDEFINED ? null : Yii::$app->BusinessUser->account->area->id,
                'display' => null,
                'message' => 9002,
            ],
        ];
    }

    public function getCustomQuantity(){
        if($this->parent_id == Area::LEVEL_UNDEFINED){
            $parentArea = Yii::$app->BusinessUser->account->area;
        }else{
            $parentArea = new Area(['id' => $this->parent_id]);
        }
        $areas = $parentArea->children;
        return array_map(function($area){
            return Handler::getMultiAttributes($area, [
                'id',
                'name',
                'level',
                'custom_quantity' => 'customQuantity',
                'has_child' => 'hasChild',
                '_func' => [
                    'level' => function($level){
                        return $level->level;
                    },
                ],
            ]);
        }, $areas);
    }
}
