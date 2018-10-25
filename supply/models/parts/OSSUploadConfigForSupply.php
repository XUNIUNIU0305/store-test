<?php
namespace supply\models\parts;

use Yii;
use common\models\parts\basic\OSSUploadConfigAbstract;
use common\ActiveRecord\OSSUploadFileAR;

class OSSUploadConfigForSupply extends OSSUploadConfigAbstract{

    //用户ID
    public $userId;

    public static function getCallbackTag(){
        return 'supply';
    }

    /**
     * 获取上传限制
     *
     * @return array
     */
    public function getUploadLimit(){
        return [
            'img_min_length' => $this->fileMinLength,
            'img_max_length' => $this->fileMaxLength,
            'img_suffix' => $this->getAuthorizeSuffix(),
        ];
    }

    protected function getFilePrefix(){
        return "s_{$this->userId}/";
    }

    protected function getCallbackIdentityId(){
        return $this->userId;
    }

    protected function getAuthorizeSuffix(){
        return [
            'jpg',
            'jpeg',
            'png',
            'gif',
        ];
    }

    protected function getUploaderType(){
        return OSSUploadFileAR::SUPPLY_USER;
    }

    protected function getExtraCallbackParams(){
        return [];
    }
}
