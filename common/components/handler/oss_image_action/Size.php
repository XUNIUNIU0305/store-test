<?php
namespace common\components\handler\oss_image_action;

use Yii;
use yii\base\InvalidParamException;

class Size extends ActionBehavior{

    use ValidatorTrait;

    const RESIZE_MODE_LFIT = 'lfit'; //等比缩放，限制在指定宽度与高度的矩形内的最大图片
    const RESIZE_MODE_MFIT = 'mfit'; //等比缩放，延伸出指定宽度与高度的矩形外的最小图片
    const RESIZE_MODE_FILL = 'fill'; //固定宽高，将延伸出指定宽度与高度的矩形外的最小图片进行居中裁剪
    const RESIZE_MODE_PAD = 'pad'; //固定宽高，缩略填充
    const RESIZE_MODE_FIXED = 'fixed'; //固定宽高，强制缩略
    const RESIZE_LIMIT_PROCESS = 0;
    const RESIZE_LIMIT_IGNORE = 1;

    /**
     * 图像缩放
     *
     * 缩放的宽度和高度必须指定其中之一；
     * 该方法在不同模式($mode)下，是否同时定义宽度和高度，所显示的图像不同；实际使用时尽量同时定义宽度与高度。
     *
     * @param integer $width 缩放后的宽度
     * @param integer $height 缩放后的高度
     * @param integer $limit 当目标缩略图大于原图时是否处理
     * @param string $mode 缩放模式
     * @param string $color 当缩放模式为self::RESIZE_MODE_PAD时，选择填充的颜色
     *
     * @return Object $this->owner
     */
    public function resize(int $width = null, int $height = null, int $limit = self::RESIZE_LIMIT_IGNORE, $mode = self::RESIZE_MODE_LFIT, $color = 'FFFFFF'){
        if(is_null($width) && is_null($height))throw new InvalidParamException('resizing image must enter either [width] or [height]');
        $config = [];
        foreach(['w' => $width, 'h' => $height] as $param => $value){
            if(is_null($value))continue;
            if($this->validateSize($value)){
                $config[$param] = $value;
            }else{
                throw new InvalidParamException('unavailable size value');
            }
        }
        if($this->validateLimit($limit)){
            $config['limit'] = $limit;
        }else{
            throw new InvalidParamException('unavailable limit value');
        }
        if($this->image && $limit == self::RESIZE_LIMIT_IGNORE){
            if($this->image->width < (int)$width || $this->image->height < (int)$height){
                return $this->owner;
            }
        }
        if($this->validateMode($mode, $color)){
            if($mode == self::RESIZE_MODE_PAD){
                $config['color'] = $color;
            }
            $config['m'] = $mode;
        }else{
            throw new InvalidParamException('undefined mode value');
        }
        $this->owner->addAction(['resize' => $config]);
        return $this->owner;
    }

    /**
     * 自动缩放图像
     *
     * 该方法仅当图像作为水印时有效，根据主图尺寸按照指定百分比缩放水印图；
     * 该方法与self::resize()方法冲突，只能设置其一；若同时使用，后调用的方法会覆盖先调用的方法。
     *
     * @param integer $percent 相对于主图的百分比大小
     *
     * @return Object $this->owner
     */
    public function autoResize(int $percent){
        if($this->validatePercent($percent)){
            $config = ['P' => $percent];
            $this->owner->addAction(['resize' => $config]);
            return $this->owner;
        }else{
            throw new InvalidParamException('unavailable percent value');
        }
    }

    protected function validatePercent(int $percent){
        return ($percent >= 1 && $percent <= 100);
    }

    protected function validateMode($mode, $color){
        if(in_array($mode, [
            self::RESIZE_MODE_LFIT,
            self::RESIZE_MODE_MFIT,
            self::RESIZE_MODE_FILL,
            self::RESIZE_MODE_PAD,
            self::RESIZE_MODE_FIXED,
        ])){
            if($mode == self::RESIZE_MODE_PAD){
                return $this->validateColor($color);
            }else{
                return true;
            }
        }else{
            return false;
        }
    }

    protected function validateLimit(int $limit){
        return in_array($limit, [
            self::RESIZE_LIMIT_PROCESS,
            self::RESIZE_LIMIT_IGNORE,
        ]);
    }

    protected function validateSize(int $size){
        return ($size >= 1 && $size <= 4096);
    }

}
