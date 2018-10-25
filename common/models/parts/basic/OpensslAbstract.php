<?php
namespace common\models\parts\basic;

use Yii;
use yii\base\Object;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;

abstract class OpensslAbstract extends Object{

    /**
     * 加密方法
     *
     * 详见openssl_get_cipher_methods(true)
     */
    public $method;

    /**
     * 加密选项
     * 默认 0
     * 其余选项：
     * OPENSSL_RAW_DATA
     * OPENSSL_ZERO_PADDING
     */
    public $options = 0;

    public function init(){
        if(!extension_loaded('openssl'))throw new InvalidCallException;
        if(!in_array($this->method, openssl_get_cipher_methods(true)))throw new InvalidConfigException;
        if(!in_array($this->options, [0, OPENSSL_RAW_DATA, OPENSSL_ZERO_PADDING], true))throw new InvalidConfigException;
    }

    /**
     * 加密信息
     *
     * @param string $data 需要加密的信息
     *
     * @return string
     */
    public function encrypt($data){
        return openssl_encrypt((string)$data, $this->method, $this->password, $this->options, $this->iv);
    }

    /**
     * 解密信息
     *
     * @param string $data 需要解密的加密信息
     *
     * @param string
     */
    public function decrypt($data){
        return openssl_decrypt($data, $this->method, $this->password, $this->options, $this->iv);
    }

    /**
     * 生成随机熵
     *
     * @return binary
     */
    final protected function generateIV(){
        return openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->method));
    }

    /**
     * 获取自定义密钥
     *
     * @return string
     */
    abstract protected function getPassword();

    /**
     * 获取加密熵
     *
     * @return string|binary
     */
    abstract protected function getIV();
}
