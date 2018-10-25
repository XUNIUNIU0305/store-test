<?php
namespace common\components\handler\oss_image_action;

use Yii;
use yii\base\InvalidParamException;
use common\models\parts\OSSImage;
use common\components\handler\oss_image_action\TextWatermark;

class Superposition extends ActionBehavior{

    use EncodeTrait;

    const IMAGE_FRONT = 0;
    const IMAGE_AFTER = 1;

    const ALIGN_TOP = 0;
    const ALIGN_CENTER = 1;
    const ALIGN_BOTTOM = 2;

    /**
     * 在图像上设置另外一张图片或文字作为水印
     *
     * 可以多次调用该方法，设置多个水印（一般选择不同位置，如左上文字水印，右下图片水印）
     *
     * @param object|array $watermark 水印对象
     * 支持对象：\common\models\parts\OSSImage（图片水印） 及 \common\components\handler\oss_image_action\TextWatermark（文字水印）
     * 当$watermark为Array时表示该水印为图文混合模式，即：将一段文字和一张图片组合成一个水印；Array格式为：
     * ```
     * $watermark = [
     *     'image' => `图片水印对象（必须）`,
     *     'text' => `文字水印对象（必须）`,
     *     'order' => `图片水印前后顺序（可选，默认值：self::IMAGE_FRONT）`,
     *     'align' => `文字、图片对齐方式（可选，默认值：self::ALIGN_TOP）`,
     *     'interval' => `文字和图片间的间距（可选，默认无间距）`,
     * ];
     * ```
     * @param string $position 水印位置，九宫格模式
     * 一一一一一一一一一一一
     * |  nw  | north|  ne  |
     * 一一一一一一一一一一一
     * | west |center| east |
     * 一一一一一一一一一一一
     * |  sw  | south|  se  |
     * 一一一一一一一一一一一
     * @param integer $transparency 水印透明度；100代表无透明
     * @param integer $borderX 水平边距，距离图片边缘的水平距离；该参数只有在水印位置是nw|west|sw|ne|east|se时有效
     * @param integer $borderY 垂直边距，距离图片边缘的垂直距离；该参数只有在水印位置是nw|north|ne|sw|south|se时有效
     * @param integer $vOffset 中线垂直偏移，水印位置根据中线往上或往下偏移；该参数只有在水印位置是west|center|east时有效
     *
     * @return Object $this->owner
     */
    public function watermark($watermark, string $position = self::POSITION_SE, int $transparency = 100, int $borderX = 10, int $borderY = 10, int $vOffset = 0){
        if($this->validateWatermark($watermark)){
            $config = $this->generateWatermarkConfig($watermark);
        }else{
            throw new InvalidParamException('unavailable watermark value');
        }
        if($this->validatePosition($position)){
            $config['g'] = $position;
        }else{
            throw new InvalidParamException('unavailable position value');
        }
        if($this->validateTransparency($transparency)){
            $config['t'] = $transparency;
        }else{
            throw new InvalidParamException('unavailable transparency value');
        }
        $validatedX = $this->validateBorder($borderX, $position, 'x');
        $validatedY = $this->validateBorder($borderY, $position, 'y');
        $validatedOffset = $this->validateOffset($vOffset, $position);
        if($validatedX === false || $validatedY === false)throw new InvalidParamException('unavailable border value');
        if($validatedOffset === false)throw new InvalidParamException('unavailable offset value');
        if($validatedX === true)$config['x'] = $borderX;
        if($validatedY === true)$config['y'] = $borderY;
        if($validatedOffset === true)$config['voffset'] = $vOffset;
        $this->owner->addAction(['watermark' => $config]);
        return $this->owner;
    }

    protected function validateOffset(int $offset, string $position){
        if($offset < -1000 || $offset > 1000)return false;
        return in_array($position, [
            self::POSITION_WEST,
            self::POSITION_CENTER,
            self::POSITION_EAST,
        ]) ? : null;
    }

    protected function validateBorder(int $border, string $position, string $type = 'x'){
        if($border < 0 || $border > 4096)return false;
        switch($type){
            case 'x':
                return in_array($position, [
                    self::POSITION_NW,
                    self::POSITION_WEST,
                    self::POSITION_SW,
                    self::POSITION_NE,
                    self::POSITION_EAST,
                    self::POSITION_SE,
                ]) ? : null;

            case 'y':
                return in_array($position, [
                    self::POSITION_NW,
                    self::POSITION_NORTH,
                    self::POSITION_NE,
                    self::POSITION_SW,
                    self::POSITION_SOUTH,
                    self::POSITION_SE,
                ]) ? : null;

            default:
                return false;
        }
    }

    protected function validateTransparency(int $transparency){
        return ($transparency >= 0 && $transparency <= 100);
    }

    protected function generateWatermarkConfig($watermark){
        if($watermark instanceof OSSImage){
            $config = [
                'image' => $this->generateImageConfig($watermark),
            ];
        }elseif($watermark instanceof TextWatermark){
            $config = $watermark->config;
        }else{
            $config = $watermark['text']->config;
            $config['image'] = $this->generateImageConfig($watermark['image']);
            isset($watermark['order']) && $config['order'] = $watermark['order'];
            isset($watermark['align']) && $config['align'] = $watermark['align'];
            isset($watermark['interval']) && $config['interval'] = $watermark['interval'];
        }
        return $config;
    }

    protected function generateImageConfig(OSSImage $image){
        $filename = $image->name;
        if(is_array($filename)){
            $filename = current($filename);
        }
        return $this->urlSafeBase64Encode($filename);
    }

    protected function validateWatermark($watermark){
        if($watermark instanceof OSSImage){
            return $this->validateImage($watermark);
        }elseif($watermark instanceof TextWatermark){
            return $this->validateText($watermark);
        }elseif(is_array($watermark)){
            if(isset($watermark['image']) &&
                isset($watermark['text']) &&
                $this->validateImage($watermark['image']) &&
                $this->validateText($watermark['text'])
            ){
                if(isset($watermark['order'])){
                    if(!$this->validateOrder($watermark['order']))return false;
                }
                if(isset($watermark['align'])){
                    if(!$this->validateAlign($watermark['align']))return false;
                }
                if(isset($watermark['interval'])){
                    if(!$this->validateInterval($watermark['interval']))return false;
                }
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    protected function validateInterval(int $interval){
        return ($interval >= 0 && $interval <= 1000);
    }

    protected function validateAlign(int $align){
        return in_array($align, [
            self::ALIGN_TOP,
            self::ALIGN_CENTER,
            self::ALIGN_BOTTOM,
        ]);
    }

    protected function validateOrder(int $order){
        return in_array($order, [
            self::IMAGE_FRONT,
            self::IMAGE_AFTER,
        ]);
    }

    protected function validateText(TextWatermark $text){
        return $text->config ? true : false;
    }

    protected function validateImage(OSSImage $image){
        $filename = $image->name;
        if(is_string($filename)){
            return true;
        }elseif(is_array($filename)){
            return (count($filename) == 1);
        }else{
            return false;
        }
    }

}
