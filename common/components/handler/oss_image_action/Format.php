<?php
namespace common\components\handler\oss_image_action;

use Yii;
use yii\base\InvalidParamException;

class Format extends ActionBehavior{

    const FORMAT_JPG = 'jpg';
    const FORMAT_PNG = 'png';
    const FORMAT_WEBP = 'webp';
    const FORMAT_BMP = 'bmp';
    const FORMAT_GIF = 'gif';

    const LOAD_NORMAL = 0;
    const LOAD_INTERLACE = 1;

    const QUALITY_RELATIVE = 'q';
    const QUALITY_ABSOLUTE = 'Q';

    /**
     * 改变图像的质量
     *
     * 只能在jpg/webp格式的图像上使用，其他格式无任何效果；
     * 相对质量 self::QUALITY_RELATIVE :
     *     对原图按照百分比进行质量压缩，如果原图质量是100%，使用90%会得到质量为90%的图片；
     *     如果原图质量是80%，使用90%会得到质量72%的图像；
     *     相对质量只能应用于jpg格式，如果原图为webp，相对质量等于绝对质量。
     * 绝对质量 self::QUALITY_ABSOLUTE :
     *     把原图质量压缩到指定百分比，如果原图质量小于该百分比，则不压缩；
     *     如果原图质量是100%，使用90%压缩，会得到质量90%的图片；
     *     如果原图质量是80%，使用90%压缩，不会有任何效果，返回质量80%的图像。
     * 
     * @param integer $percent 压缩百分比
     * @param string $quality 压缩类型
     *
     * @return Object $this->owner
     */
    public function quality(int $percent, string $quality = self::QUALITY_ABSOLUTE){
        if($this->validateQuality($quality) && $this->validatePercent($percent)){
            $config = [$quality => $percent];
            $this->owner->addAction(['quality' => $config]);
            return $this->owner;
        }else{
            throw new InvalidParamException('unavailable percent or quality value');
        }
    }

    protected function validateQuality(string $quality){
        return in_array($quality, [
            self::QUALITY_RELATIVE,
            self::QUALITY_ABSOLUTE,
        ]);
    }

    protected function validatePercent(int $percent){
        return ($percent >= 1 && $percent <= 100);
    }

    /**
     * 转换图像的呈现方式
     *
     * 只有当图像为jpg时该方法有效；
     * 图像格式为jpg时有两种呈现方式：
     * 1.自上而下的扫描式（默认）
     * 2.先模糊后逐渐清晰（网络环境比较差时明显）
     * 要对非jpg格式的图像使用该方法时，先转换图像格式。
     *
     * @param integer $load 呈现方式
     *
     * @return Object $this->owner
     */
    public function interlace(int $load = self::LOAD_INTERLACE){
        if($this->validateLoad($load)){
            $config = $load;
            $this->owner->addAction(['interlace' => $config]);
            return $this->owner;
        }else{
            throw new InvalidParamException('unavailable load value');
        }
    }

    protected function validateLoad(int $load){
        return in_array($load, [
            self::LOAD_NORMAL,
            self::LOAD_INTERLACE,
        ]);
    }

    /**
     * 图像格式转换
     *
     * 将图像转换成gif格式时，如果原图非gif格式则仍按原图格式保存
     *
     * @param string $format 需要转换成的格式
     *
     * @return Object $this->owner
     */
    public function convert(string $format){
        if($this->validateFormat($format)){
            if($format == self::FORMAT_GIF && $this->image){
                if($this->image->mimetype != 'image/gif')return $this->owner;
            }
            $this->owner->addAction(['format' => $format]);
        }else{
            throw new InvalidParamException('unavailable format value');
        }
        return $this->owner;
    }

    protected function validateFormat(string $format){
        return in_array($format, [
            self::FORMAT_JPG,
            self::FORMAT_PNG,
            self::FORMAT_WEBP,
            self::FORMAT_BMP,
            self::FORMAT_GIF,
        ]);
    }

}
