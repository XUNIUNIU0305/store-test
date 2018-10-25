<?php
namespace common\components\handler\oss_image_action;

use Yii;
use yii\base\InvalidParamException;

class Spin extends ActionBehavior{

    const AUTO_ORIENT_ON = 1; //开启自动旋转
    const AUTO_ORIENT_OFF = 0; //关闭自动旋转

    /**
     * 使图像按顺时针旋转
     *
     * 旋转图像可能会导致图像的尺寸变大；
     * 旋转对图像的尺寸有限制，图像的宽度或高度不能超过4096。
     *
     * @param integer $angle 旋转的角度
     *
     * @return Object $this->owner
     */
    public function rotate(int $angle){
        if($this->validateAngle($angle)){
            if($this->image){
                if($this->image->width > 4096 || $this->image->height > 4096)return $this->owner;
            }
            $config = $angle;
            $this->owner->addAction(['rotate' => $config]);
            return $this->owner;
        }else{
            throw new InvalidParamException('unavailable angle value');
        }
    }

    protected function validateAngle(int $angle){
        return ($angle >= 0 && $angle <= 360);
    }

    /**
     * 使图像自动旋转
     *
     * 某些手机拍摄出来的照片可能带有旋转参数（存放在照片exif信息里面），可以设置是否对这些照片进行自适应方向的旋转；
     * 如果原图没有旋转参数，则该方法无任何效果；
     * 进行自适应方向旋转，要求原图的宽度和高度必须小于4096。
     *
     * @param integer $orient 否则启用自适应旋转
     *
     * @return Object $this->owner
     */
    public function autoOrient(int $orient = self::AUTO_ORIENT_ON){
        if($this->validateOrient($orient)){
            if($this->image){
                if($this->image->width > 4096 || $this->image->height > 4096)return $this->owner;
            }
            $config = $orient;
            $this->owner->addAction(['auto-orient' => $config]);
            return $this->owner;
        }else{
            throw new InvalidParamException('unavailable orient value');
        }
    }

    protected function validateOrient(int $orient){
        return in_array($orient, [
            self::AUTO_ORIENT_ON,
            self::AUTO_ORIENT_OFF,
        ]);
    }
}
