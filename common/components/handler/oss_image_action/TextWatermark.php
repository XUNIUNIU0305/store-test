<?php
namespace common\components\handler\oss_image_action;

use Yii;
use yii\base\Object;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;

class TextWatermark extends Object{

    use EncodeTrait;
    use ValidatorTrait;

    const FILL_ON = 1;
    const FILL_OFF = 0;

    const FONT_WQYZH = 'wqy-zenhei'; //文泉驿正黑
    const FONT_WQWMH = 'wqy-microhei'; //文泉微米黑
    const FONT_FZSS = 'fangzhengshusong'; //方正书宋
    const FONT_FZKT = 'fangzhengkaiti'; //方正楷体
    const FONT_FZHT = 'fangzhengheiti'; //方正黑体
    const FONT_FZFS = 'fangzhengfangsong'; //方正仿宋
    const FONT_DSF = 'droidsansfallback'; //DroidSansFallback

    public $text; //文字内容；经过Base64转码后最大长度不能超过64
    public $font; //文字字体；见本类常量
    public $color; //文字颜色；6位16进制字符
    public $size; //文字尺寸；单位px
    public $shadow; //文字阴影透明度
    public $rotate; //文字顺时针旋转角度
    public $fill;  //是否开启文字铺满效果；0表示不开启

    private $_text;
    private $_font;
    private $_color;
    private $_size;
    private $_shadow;
    private $_rotate;
    private $_fill;

    public function init(){
        $config = [
            'text',
            'font',
            'color',
            'size',
            'shadow',
            'rotate',
            'fill',
        ];
        foreach($config as $attribute){
            if(!is_null($this->{$attribute})){
                call_user_func([$this, 'set' . ucfirst($attribute)], $this->{$attribute});
            }
        }
    }

    public function getConfig(){
        if(is_null($this->_text))return false;
        return array_filter([
            'text' => $this->_text,
            'type' => $this->_font,
            'color' => $this->_color,
            'size' => $this->_size,
            'shadow' => $this->_shadow,
            'rotate' => $this->_rotate,
            'fill' => $this->_fill,
        ]);
    }

    public function setText(string $text){
        if(empty($text))throw new InvalidParamException('text is required');
        $encodedText = $this->urlSafeBase64Encode($text);
        if(strlen($encodedText) > 64)throw new InvalidParamException('text is too long');
        $this->_text = $encodedText;
        return $this;
    }

    public function setFill(int $fill){
        if($this->validateFill($fill)){
            $this->_fill = $fill;
            return $this;
        }else{
            throw new InvalidParamException('unavailable fill value');
        }
    }

    protected function validateFill(int $fill){
        return in_array($fill, [
            self::FILL_ON,
            self::FILL_OFF,
        ]);
    }

    public function setRotate(int $rotate){
        if($this->validateRotate($rotate)){
            $this->_rotate = $rotate;
            return $this;
        }else{
            throw new InvalidParamException('unavailable rotate value');
        }
    }

    protected function validateRotate(int $rotate){
        return ($rotate >= 0 && $rotate <= 360);
    }

    public function setShadow(int $shadow){
        if($this->validateShadow($shadow)){
            $this->_shadow = $shadow;
            return $this;
        }else{
            throw new InvalidParamException('unavailable shadow value');
        }
    }

    protected function validateShadow(int $shadow){
        return ($shadow > 0 && $shadow <= 100);
    }

    public function setSize(int $size){
        if($this->validateSize($size)){
            $this->_size = $size;
            return $this;
        }else{
            throw new InvalidParamException('unavailable size value');
        }
    }

    protected function validateSize(int $size){
        return ($size > 0 && $size <= 1000);
    }

    public function setColor(string $color){
        $color = strtoupper($color);
        if($this->validateColor($color)){
            $this->_color = $color;
            return $this;
        }else{
            throw new InvalidParamException('unavailable color value');
        }
    }

    public function setFont(string $font){
        if($this->validateFont($font)){
            $this->_font = $this->urlSafeBase64Encode($font);
            return $this;
        }else{
            throw new InvalidParamException('unavailable font value');
        }
    }

    protected function validateFont(string $font){
        return in_array($font, [
            self::FONT_SQYZH,
            self::FONT_WQWMH,
            self::FONT_FZSS,
            self::FONT_FZKT,
            self::FONT_FZHT,
            self::FONT_FZFS,
            self::FONT_DSF,
        ]);
    }
}
