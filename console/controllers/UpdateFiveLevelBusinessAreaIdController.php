<?php
/**
 * Created by PhpStorm.
 * User: forrestgao
 * Date: 18-4-4
 * Time: 下午2:17
 */

namespace console\controllers;

use common\ActiveRecord\BusinessAreaAR;
use common\ActiveRecord\CustomUserAR;
use console\controllers\basic\Controller;
use Yii;

class UpdateFiveLevelBusinessAreaIdController extends Controller
{

    /*
     * 修改五级的 business_area_id
     * 用法：在命令行应用主目录下执行 php yii update-five-level-business-area-id
     */
    public function actionIndex()
    {
        return 0;
        $select = ['pf_custom_user.id', 'pf_business_area.parent_business_area_id'];
        $quaternarys = CustomUserAR::find()
                                    ->select($select)
                                    ->leftJoin(
                                        'pf_business_area',
                                        'pf_custom_user.business_area_id = pf_business_area.id')
                                    ->asArray()
                                    ->all();

        foreach ($quaternarys as $quaternary) {
            $customUserId = $quaternary['id'];
            $quaternary = $quaternary['parent_business_area_id'];
            $tertiary = $this->getParentBusinessAreaId($quaternary);
            $secondary = $this->getParentBusinessAreaId($tertiary);
            $top = $this->getParentBusinessAreaId($secondary);
            if ($quaternary === false || $tertiary === false || $secondary === false || $top === false) {
                return false;
            }

            if ($this->updateFiveLevelBusinessAreaId($customUserId, $quaternary, $tertiary, $secondary, $top)) {
                echo "update {$customUserId} success\n";
            } else {
                echo "update {$customUserId} failed\n";
            }
        }
    }

    /*
     * 获得父级的 area_id
     * @param string $businessAreaId
     * @return string|bool parent_business_area_id
     */
    public function getParentBusinessAreaId($businessAreaId)
    {
        $select = 'parent_business_area_id';
        $result = BusinessAreaAR::find()
                                ->select($select)
                                ->where(['id' => $businessAreaId])
                                ->scalar();
        return isset($result) ? $result : false;
    }

    /*
     * 修改五级的 business_area_id
     * @param string $customUserId
     * @param string $quaternary
     * @param string $tertiary
     * @param string $secondary
     * @param string $top
     * @return boolean
     */
    public function updateFiveLevelBusinessAreaId($customUserId, $quaternary, $tertiary, $secondary, $top)
    {
        $customUser = CustomUserAR::findOne($customUserId);
        $customUser->business_top_area_id = $top;
        $customUser->business_secondary_area_id = $secondary;
        $customUser->business_tertiary_area_id = $tertiary;
        $customUser->business_quaternary_area_id = $quaternary;
        return $customUser->update() === false ? false : true;
    }

    public function actionRefreshRegistercode(){
        $codeIds = Yii::$app->RQ->AR(new \common\ActiveRecord\CustomUserRegistercodeAR)->column([
            'select' => [
                'id',
            ],
            'where' => [
                'business_top_area_id' => 0,
            ],
        ]);
        foreach($codeIds as $codeId){
            $registerCode = new \custom\models\parts\RegisterCode([
                'id' => $codeId,
            ]);
            $fifthArea = $registerCode->area;
            Yii::$app->RQ->AR(\common\ActiveRecord\CustomUserRegistercodeAR::findOne($codeId))->update([
                'business_quaternary_area_id' => ($quaternaryArea = $fifthArea->parent)->id,
                'business_tertiary_area_id' => ($tertiaryArea = $quaternaryArea->parent)->id,
                'business_secondary_area_id' => ($secondaryArea = $tertiaryArea->parent)->id,
                'business_top_area_id' => $secondaryArea->parent->id,
            ]);
        }
        return 0;
    }
}
