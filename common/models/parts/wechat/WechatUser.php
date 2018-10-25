<?php
namespace common\models\parts\wechat;

use Yii;
use yii\base\Object;
use common\components\handler\Handler;
use common\ActiveRecord\WechatUserAR;
use yii\base\InvalidConfigException;

class WechatUser extends Object{

    public $id;
    public $unionId;

    protected $AR;

    public function init(){
        if(!is_null($this->id)){
            $this->AR = WechatUserAR::findOne($this->id);
        }
        if(!is_null($this->unionId)){
            $this->AR = WechatUserAR::findOne([
                'unionid_hash' => Handler::generateBKDRHash($this->unionId),
                'unionid' => $this->unionId,
            ]);
        }
        if(!$this->AR){
            throw new InvalidConfigException('Unknown User');
        }
        $this->id = $this->AR->id;
    }

    public function getOpenId(){
        return $this->AR->openid;
    }

    public function getNickname(){
        return $this->AR->nickname;
    }

    public function getSex(){
        return $this->AR->sex;
    }

    public function getProvince(){
        return $this->AR->province;
    }

    public function getCity(){
        return $this->AR->city;
    }

    public function getCountry(){
        return $this->AR->country;
    }

    public function getHeadImageUrl(){
        return $this->AR->headimgurl;
    }

    public function getPrivilege(){
        return empty($this->AR->privilege) ? '' : unserialize($this->AR->privilege);
    }

    public function getUnionId(){
        return $this->AR->unionid;
    }

    public function getUnionIdHash(){
        return $this->AR->unionid_hash;
    }
}
