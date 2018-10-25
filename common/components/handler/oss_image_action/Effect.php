<?php
namespace common\components\handler\oss_image_action;

use Yii;
use yii\base\InvalidParamException;

class Effect extends ActionBehavior{

    /**
     * 对图像进行锐化调整
     *
     * @param integer $sharpenParam 锐化数值；参数越大越清晰；推荐值为100
     *
     * @return Object $this->owner
     */
    public function sharpen(int $sharpenParam){
        if($this->validateSharpenParam($sharpenParam)){
            $config = $sharpenParam;
            $this->owner->addAction(['sharpen' => $config]);
            return $this->owner;
        }else{
            throw new InvalidParamException('unavailable sharpen param value');
        }
    }

    protected function validateSharpenParam(int $sharpenParam){
        return ($sharpenParam >= 50 && $sharpenParam <= 399);
    }

    /**
     * 对图像进行对比度调节
     *
     * @param integer $ratio 对比度；0表示原图对比度，小于0表示低于原图对比度，大于0表示高于原图对比度
     *
     * @return Object $this->owner
     */
    public function contrast(int $ratio){
        if($this->validateRatio($ratio)){
            $config = $ratio;
            $this->owner->addAction(['contrast' => $config]);
            return $this->owner;
        }else{
            throw new InvalidParamException('unavailable ratio value');
        }
    }

    protected function validateRatio(int $ratio){
        return ($ratio >= -100 && $ratio <= 100);
    }

    /**
     * 对图像进行亮度调节
     *
     * @param integer $light 亮度；0表示原图亮度，小于0表示低于原图亮度，大于0表示高于原图亮度
     *
     * @return Object $this->owner
     */
    public function bright(int $light){
        if($this->validateLight($light)){
            $config = $light;
            $this->owner->addAction(['bright' => $config]);
            return $this->owner;
        }else{
            throw new InvalidParamException('unavailable light value');
        }
    }

    protected function validateLight(int $light){
        return ($light >= -100 && $light <= 100);
    }

    /**
     * 对图像进行模糊操作
     *
     * @param integer $radius 模糊半径
     * @param integer $standardDeviation 正态分布的标准差
     *
     * @return Object $this->owner
     */
    public function blur(int $radius, int $standardDeviation){
        if($this->validateRadius($radius) && $this->validateStandardDeviation($standardDeviation)){
            $config = [
                'r' => $radius,
                's' => $standardDeviation,
            ];
            $this->owner->addAction(['blur' => $config]);
            return $this->owner;
        }else{
            throw new InvalidParamException('unavailable radius or standard deviation value');
        }
    }

    protected function validateRadius(int $radius){
        return ($radius >= 1 && $radius <= 50);
    }

    protected function validateStandardDeviation(int $standardDeviation){
        return ($standardDeviation >= 1 && $standardDeviation <= 50);
    }
}
