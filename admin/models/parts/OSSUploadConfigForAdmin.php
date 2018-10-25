<?php
namespace admin\models\parts;

use common\models\parts\basic\OSSUploadConfigAbstract;
use common\ActiveRecord\OSSUploadFileAR;

class OSSUploadConfigForAdmin extends OSSUploadConfigAbstract
{

    //用户ID
    public $userId;

    public static function getCallbackTag()
    {
        return 'admin';
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
        return "a_{$this->userId}/";
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
        return OSSUploadFileAR::ADMIN_USER;
    }

    protected function getExtraCallbackParams()
    {
        return [];
    }
}
