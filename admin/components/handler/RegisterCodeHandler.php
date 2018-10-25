<?php
namespace admin\components\handler;

use Yii;
use common\components\handler\Handler;
use common\ActiveRecord\CustomUserRegistercodeAR;
use custom\models\parts\RegisterCode;
use yii\data\ActiveDataProvider;
use business\models\parts\Area;

class RegisterCodeHandler extends Handler{

    public static function createCustomCode(int $quantity, $return = 'throw'){
        $area = new Area(['id' => Area::DEFAULT_FIFTH_ID]);
        return \custom\components\handler\RegistercodeHandler::createPartnerCode($quantity, $area, $return);
    }

    public static function provideCustomCodes($used = 'all', $currentPage, $pageSize){
        $currentPage = (int)$currentPage or $currentPage = 1;
        $pageSize = (int)$pageSize or $pageSize = 1;
        $used = $used === 'all' ? null : ($used ? RegisterCode::STATUS_USED : RegisterCode::STATUS_UNUSED);
        return new ActiveDataProvider([
            'query' => CustomUserRegistercodeAR::find()->select(['id', 'account', 'used', 'create_time', 'register_time'])->where(['business_area_id' => Area::DEFAULT_FIFTH_ID])->andWhere(['level' => [RegisterCode::LEVEL_PARTNER, RegisterCode::LEVEL_IN_SYSTEM]])->andWhere(['is_available' => RegisterCode::STATUS_AVAILABLE])->andFilterWhere(['used' => $used])->asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => [
                    'create_unixtime' => SORT_DESC,
                ],
            ],
        ]);
    }
}
