<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/8/10 0010
 * Time: 10:29
 */

namespace mobile\components\validators;


use common\models\Validator;

/**
 * 验证图片数组
 * Class ImageValidator
 * @package mobile\components\validators
 */
class ImageValidator extends Validator
{
    public function validateValue($value)
    {
        if(!is_array($value) || count($value) > 8){
//            $this->addError($attribute, 9002);
            return 9002;
        }
        return true;
    }
}