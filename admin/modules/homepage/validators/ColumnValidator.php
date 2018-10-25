<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-31
 * Time: 上午10:16
 */

namespace admin\modules\homepage\validators;


use common\ActiveRecord\HomepageColumnAR;
use yii\validators\Validator;

class ColumnValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        if(!HomepageColumnAR::find()->where(['id' => $model->$attribute])->exists()){
            $this->addError($model, $attribute, 5391);
        }
    }
}