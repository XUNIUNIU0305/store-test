<?php
namespace business\models\handler;

use Yii;
use common\components\handler\Handler;
use common\ActiveRecord\CustomUserRegistercodeAR;
use yii\data\ActiveDataProvider;
use business\models\parts\Area;
use custom\models\parts\RegisterCode;

class CustomUserRegistercodeHandler extends Handler{

    public static function provide($used, int $currentPage, int $pageSize, Area $area = null){
        if($currentPage <= 0)$currentPage = 1;
        if($pageSize <= 0)$pageSize = 1;
        if(is_null($area)){
            $areaId = null;
        }else{
            $areaId = $area->id;
        }
        if(!in_array($used, [0, 1]))$used = null;
        return new ActiveDataProvider([
            'query' => CustomUserRegistercodeAR::find()->
            select(['account', 'business_area_id', 'used', 'create_time', 'register_time'])->
            where(['level' => [RegisterCode::LEVEL_PARTNER, RegisterCode::LEVEL_IN_SYSTEM]])->
            andWhere(['is_available' => RegisterCode::STATUS_AVAILABLE])->
            andFilterWhere(['business_area_id' => $areaId])->
            andFilterWhere(['used' => $used]),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);
    }
}
