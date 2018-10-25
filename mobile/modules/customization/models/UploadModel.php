<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/8/9 0009
 * Time: 14:34
 */

namespace mobile\modules\customization\models;


use common\models\Model;
use custom\models\parts\OSSUploadConfigForCustom;

class UploadModel extends Model
{
    const SCE_PERMISSION = 'permission';

    public $suffix;

    public function scenarios()
    {
        return [
            self::SCE_PERMISSION => [
                'suffix'
            ]
        ];
    }

    public function rules()
    {
        return [
            [
                ['suffix'],
                'string',
                'message' => 9001
            ],
            [
                ['suffix'],
                'required',
                'message' => 9001
            ]
        ];
    }

    /**
     * @return array|bool
     */
    public function permission()
    {
        $config = new OSSUploadConfigForCustom([
            'userId' => \Yii::$app->user->id,
            'fileSuffix' => $this->suffix,
            'fileMaxLength' => 5242880,
        ]);
        if($permission = $config->getPermission()){
            return $permission;
        }else{
            $this->addError('getOSSUploadPermission', 11023);
            return false;
        }
    }
}
