<?php
namespace common\components\handler\oss_image_action;

use Yii;
use yii\base\InvalidParamException;

class Cut extends ActionBehavior{

    const INDEX_CROP_AXIS_X = 'x';
    const INDEX_CROP_AXIS_Y = 'y';

    protected function getImageShortSide(){
        if($this->image){
            return ($this->image->width > $this->image->height ? $this->image->height : $this->image->width);
        }else{
            return false;
        }
    }

    /**
     * 图像裁剪 - 圆角矩形
     *
     * 根据指定的圆角大小裁剪出圆角矩形图像；
     * 如果指定半径大于原图最大内切圆的半径，则圆角的大小仍然是图片的最大内切圆；
     * 如果图片的最终格式是png、webp、bmp等支持透明通道的图片，那么图片非圆形区域将会以透明填充；
     * 如果图片的最终格式是jpg，那么非圆形区域以白色填充。
     *
     * @param integer $radius 圆角半径
     *
     * @return Object $this->owner
     */
    public function roundedCorners(int $radius){
        $config = [];
        if($this->validateRadius($radius)){
            if($this->image){
                $maxImageRadius = $this->imageShortSide * 0.5;
                $radius = $radius > $maxImageRadius ? $maxImageRadius : $radius;
            }
            $config['r'] = $radius;
        }else{
            throw new InvalidParamException('unavailable rounded corners radius value');
        }
        $this->owner->addAction(['rounded-corners' => $config]);
        return $this->owner;
    }

    protected function validateRadius(int $radius){
        return ($radius >= 1 && $radius <= 4096);
    }

    /**
     * 图像裁剪 - 索引切割
     *
     * 将图像分成x、y轴，按指定长度切割，指定索引，取出指定的区域；
     * 如果指定的索引大于切割后范围，将返回原图。
     *
     * @param string $axis 坐标轴；指定根据x或y轴进行切割
     * @param integer $length 切割后每块图片的长度
     * @param integer $indexBlock 选择切割后的第几块图像；0表示第一块
     *
     * @return Object $this->owner
     */
    public function indexCrop(string $axis, int $length, int $indexBlock){
        $config = [];
        if($length < 1)throw new InvalidParamException('unavailable index crop length value');
        if($this->validateAxis($axis)){
            $config[$axis] = $length;
        }
        if($indexBlock < 0){
            throw new InvalidParamException('unavailable index crop index block value');
        }else{
            $config['i'] = $indexBlock;
        }
        if($this->image){
            switch($axis){
                case self::INDEX_CROP_AXIS_X:
                    $totalBlock = ceil($this->image->width / $length);
                    break;

                case self::INDEX_CROP_AXIS_Y:
                    $totalBlock = ceil($this->image->height / $length);
                    break;

                default:
                    throw new \Exception;
            }
            if(($indexBlock + 1) > $totalBlock)return $this->owner;
        }
        $this->owner->addAction(['indexcrop' => $config]);
        return $this->owner;
    }

    protected function validateAxis(string $axis){
        return in_array($axis, [
            self::INDEX_CROP_AXIS_X,
            self::INDEX_CROP_AXIS_Y,
        ]);
    }

    /**
     * 图像裁剪
     *
     * 指定裁剪的起始点以及裁剪的宽高来决定裁剪区域；
     * 如果指定的起始横、纵坐标大于原图将会返回错误；
     * 如果从起点开始指定的宽度和高度超过了原图，将会直接裁剪到原图结尾。
     *
     * @param integer $x 裁剪起点横坐标
     * @param integer $y 裁剪起点纵坐标
     * @param integer $width 裁剪宽度
     * @param integer $height 裁剪高度
     * @param string $position 裁剪的原点位置，九宫格的格式
     * 一一一一一一一一一一一
     * |  nw  | north|  ne  |
     * 一一一一一一一一一一一
     * | west |center| east |
     * 一一一一一一一一一一一
     * |  sw  | south|  se  |
     * 一一一一一一一一一一一
     *
     * @return Object $this->owner
     */
    public function crop(int $x, int $y, int $width = null, int $height = null, string $position = self::POSITION_NW){
        if($x < 0 || $y < 0)throw new InvalidParamException('unavailable crop coordinate');
        if($this->image){
            if($x > $this->image->width || $y > $this->image->height)return $this->owner;
        }
        $config = [
            'x' => $x,
            'y' => $y,
        ];
        if($this->validatePosition($position)){
            $config['g'] = $position;
        }else{
            throw new InvalidParamException('unavailable crop position');
        }
        foreach(['w' => $width, 'h' => $height] as $name => $value){
            if(is_null($value))continue;
            if($value < 0)throw new InvalidParamException('unavailable crop length value');
            $config[$name] = $value;
        }
        $this->owner->addAction(['crop' => $config]);
        return $this->owner;
    }

    /**
     * 图像裁剪 - 内切圆
     *
     * 以图像中心为圆心、$radius为半径裁剪出圆形图像；
     * 如果图像的最终格式是png、webp、bmp等支持透明通道的图片，那么图像非圆形区域将会以透明填充；
     * 如果图像的最终格式是jpg，那么非圆形区域以白色进行填充。
     *
     * @param integer $radius 圆形区域的半径；不能超过原图的最小边的一半，如果超过则圆的大小仍然是原图的最大内切圆
     * 
     * @return Object $this->owner
     */
    public function circle(int $radius){
        if($radius <= 0)throw new InvalidParamException('unavailable radius value');
        if($this->image){
            $imageMaxRadius = $this->imageShortSide * 0.5;
            $radius = $radius > $imageMaxRadius ? $imageMaxRadius : $radius;
        }
        $config = ['r' => $radius];
        $this->owner->addAction(['circle' => $config]);
        return $this->owner;
    }
}
