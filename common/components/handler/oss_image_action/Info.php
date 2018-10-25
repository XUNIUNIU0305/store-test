<?php
namespace common\components\handler\oss_image_action;

use Yii;
use yii\base\InvalidParamException;

class Info extends ActionBehavior{

    /**
     * 获取图像的基本信息和exif信息（如果有的话），访问图像链接返回JSON数据
     *
     * 基本信息包括：图像宽度、长度、文件大小、格式
     *
     * 其他资料参考：https://help.aliyun.com/document_detail/44975.html
     *
     * @return Object $this->owner
     */
    public function info(){
        $this->owner->addAction(['info' => 0]);
        return $this->owner;
    }

    /**
     * 获取图像的平均色调，访问图像链接返回JSON数据
     *
     * 响应示例：'{"RGB": "0x5c783b"}'
     * 
     * @return Object $this->owner
     */
    public function averageHue(){
        $this->owner->addAction(['average-hue' => 0]);
        return $this->owner;
    }
}
