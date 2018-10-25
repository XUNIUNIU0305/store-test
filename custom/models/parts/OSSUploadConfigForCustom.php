<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/23
 * Time: 11:04
 */

namespace custom\models\parts;


use common\models\parts\basic\OSSUploadConfigAbstract;
use common\ActiveRecord\OSSUploadFileAR;

class OSSUploadConfigForCustom extends OSSUploadConfigAbstract
{

    //用户ID
    public $userId;

    public static function getCallbackTag()
    {
        return 'custom';
    }

    /**
     * 获取上传限制
     * @return array
     */
    public function getUploadLimit()
    {
        return [
            'img_min_length' => $this->fileMinLength,
            'img_max_length' => $this->fileMaxLength,
            'img_suffix' => $this->getAuthorizeSuffix(),
        ];
    }

    protected function getFilePrefix()
    {
        return "c_{$this->userId}/";
    }

    protected function getCallbackIdentityId()
    {
        return $this->userId;
    }

    protected function getAuthorizeSuffix()
    {
        return [
            'jpg',
            'jpeg',
            'png',
            'gif',
        ];
    }

    protected function getUploaderType()
    {
        return OSSUploadFileAR::CUSTOM_USER;
    }

    protected function getExtraCallbackParams()
    {
        return [];
    }
}
