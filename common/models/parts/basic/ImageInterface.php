<?php
namespace common\models\parts\basic;

interface ImageInterface{
    
    /**
     * 获取图片名称
     *
     * @return string
     */
    public function getName();

    /**
     * 获取图片尺寸bytes
     *
     * @return integer
     */
    public function getSize();

    /**
     * 获取图片类型
     *
     * @return string
     */
    public function getMimetype();

    /**
     * 获取图片宽度
     *
     * @return integer
     */
    public function getWidth();

    /**
     * 获取图片高度
     *
     * @return integer
     */
    public function getHeight();
}
