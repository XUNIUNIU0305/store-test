<?php
namespace common\components\handler;

use Yii;
use yii\base\InvalidParamException;
use common\models\parts\OSSImage;

class OSSImageHandler extends Handler{

    protected $commonHeader = '?x-oss-process=image/';

    private $_action = [];
    private $_actions;

    private $_asWatermark = false;

    private $_image;

    public function behaviors(){
        return [
            'size' => '\common\components\handler\oss_image_action\Size',
            'cut' => '\common\components\handler\oss_image_action\Cut',
            'format' => '\common\components\handler\oss_image_action\Format',
            'spin' => '\common\components\handler\oss_image_action\Spin',
            'effect' => '\common\components\handler\oss_image_action\Effect',
            'info' => '\common\components\handler\oss_image_action\Info',
            'superposition' => '\common\components\handler\oss_image_action\Superposition',
        ];
    }

    /**
     * 装载图片对象
     *
     * 某些图像处理动作（裁剪、缩放等）需要对原图尺寸进行判断，错误的参数可能使处理失败，导致图片无法显示；
     * 装载图像后执行有限制的处理动作时可自动过滤/转换参数，保证图像能被正常显示
     *
     * @param Object $image OSSImage对象
     *
     * @return Object OSSImageHandler对象
     */
    public static function load(OSSImage $image){
        if(is_numeric($image->images)){
            $handler = new OSSImageHandler;
            $handler->_image = $image;
            return $handler;
        }else{
            return false;
        }
    }

    /**
     * 对指定对象应用图像处理动作
     *
     * 在未装载图片对象时应用于对象的处理参数可能是无效的，需确认图片最终显示效果
     *
     * @param NULL|Object|Array 应用对象；当处理器已装载图片对象则无需指定
     *
     * @return boolean
     */
    public function apply($image = null){
        $actions = $this->generateActions();
        if(is_null($image)){
            if($this->_image instanceof OSSImage){
                $this->_image->setAdditionalAction($actions);
                return true;
            }else{
                return false;
            }
        }elseif($image instanceof OSSImage){
            $image->setAdditionalAction($actions);
            return true;
        }elseif(is_array($image)){
            if(empty($image))return false;
            if(count($image) == count(array_filter($image, function($image){
                return $image instanceof OSSImage;
            }))){
                    foreach($image as $one){
                        $one->setAdditionalAction($actions);
                    }
                    return true;
                }else{
                    return false;
                }
        }else{
            return false;
        }
    }

    /**
     * 获取已装载的图片对象
     *
     * @return NULL|Object
     */
    public function getImage(){
        return $this->_image;
    }

    /**
     * 添加处理动作
     *
     * @param array $action
     *
     * @return true
     * @throw InvalidParamException
     */
    public function addAction(array $action){
        foreach($action as $actionName => $actionValue){
            if(is_int($actionName))throw new InvalidParamException('unavailable action name');
            if(is_array($actionValue) && !empty($actionValue)){
                $params = [];
                foreach($actionValue as $paramName => $paramValue){
                    $params[] = $paramName . '_' . $paramValue;
                }
                if($actionName == 'watermark'){
                    $this->_action[$actionName][] = implode(',', $params);
                }else{
                    $this->_action[$actionName] = implode(',', $params);
                }
            }elseif(is_string($actionValue) && !empty($actionValue)){
                $this->_action[$actionName] = $actionValue;
            }elseif(is_int($actionValue)){
                $this->_action[$actionName] = $actionValue;
            }else{
                throw new InvalidParamException('unavailable action value');
            }
        }
        return true;
    }

    /**
     * 将处理器应用对象作为水印
     *
     * @param boolean $watermark
     *
     * @return Object $this
     */
    public function asWatermark(bool $watermark = true){
        $this->_asWatermark = $watermark;
        return $this;
    }

    protected function filterActions(array $actions){
        $supportActions = [
            'resize',
            'crop',
            'indexcrop',
            'rounded-corners',
            'auto-orient',
            'rotate',
        ];
        $leftActions = [];
        foreach($supportActions as $action){
            isset($actions[$action]) && $leftActions[$action] = $actions[$action];
        }
        return $leftActions;
    }

    protected function generateActions(){
        if(is_null($this->_actions)){
            if($this->_asWatermark){
                $this->_action = $this->filterActions($this->_action);
            }
            if(empty($this->_action)){
                $this->_actions = '';
            }else{
                $this->_actions = $this->commonHeader . $this->comboAction($this->_action);
                return $this->_actions;
            }
        }
        return $this->_actions;
    }

    protected function comboAction(array $action){
        $actions = [];
        foreach($action as $name => $value){
            if(is_array($value)){
                foreach($value as $extraValue){
                    $actions[] = $name . ',' . $extraValue;
                }
            }else{
                $actions[] = $name . ',' . $value;
            }
        }
        return implode('/', $actions);
    }

    /**
     * 重置处理器操作
     *
     * @param string $action 操作名称；未指定则重置全部操作
     *
     * @return boolean
     */
    public function reset(string $action = null){
        if(is_null($action)){
            $this->_action = [];
            $this->_actions = null;
            return true;
        }else{
            if(isset($this->_action[$action])){
                unset($this->_action[$action]);
                $this->_actions = null;
                return true;
            }else{
                return false;
            }
        }
    }
}
