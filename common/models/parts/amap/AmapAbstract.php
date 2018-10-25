<?php
namespace common\models\parts\amap;

use Yii;
use Curl\Curl;
use yii\base\Object;
use yii\base\InvalidCallException;
use yii\base\UnknownPropertyException;

abstract class AmapAbstract extends Object{

    private $_params = [];
    private $_globalParams = [
        'key' => true,
        'sig' => false,
        'output' => 'JSON',
        'callback' => false,
    ];
    private $_requestUrl = [
        self::ACTION_GEOCODE => '/geocode/geo',
        self::ACTION_DISTANCE => '/distance',
    ];

    const ACTION_GLOBAL_URL = 'https://restapi.amap.com/v3';
    const ACTION_GEOCODE = 'geocode';
    const ACTION_DISTANCE = 'distance';

    abstract protected function requiredParams() : array;

    abstract protected function action() : string;

    public function init(){
        $this->_params = array_merge($this->_globalParams, static::requiredParams(), $this->_params);
        $this->_setParam('key', Yii::$app->params['AMAP_Key']);
    }

    public function achieve($return = 'throw'){
        if(!$params = $this->_getRequestParams()){
            return Yii::$app->EC->callback($return, 'required params is missing');
        }
        if(isset($params['sig'])){
            /* 生成签名 */
        }
        $curl = new Curl;
        $curl->setDefaultJsonDecoder(true);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $curl->setOpt(CURLOPT_FAILONERROR, false);
        $curl->setOpt(CURLOPT_RETURNTRANSFER, true);
        $curl->get(self::ACTION_GLOBAL_URL . $this->_requestUrl[static::action()], $params);
        if($curl->error){
            return Yii::$app->EC->callback($return, 'request failed');
        }else{
            if($curl->response['status'] == '1'){
                return $curl->response;
            }else{
                return Yii::$app->EC->callback($return, $curl->response['info']);
            }
        }
    }

    public function setGlobalParams(array $params){
        $this->_globalParams = $params;
        $this->init();
    }

    private function _getRequestParams(){
        $params = $this->_params;
        foreach($params as $k => $param){
            if($param === true && $param !== 'sig'){
                return false;
            }
            if($param === false || $param === null)unset($params[$k]);
        }
        return $params;
    }

    public function __get($name){
        try{
            return parent::__get($name);
        }catch(InvalidCallException $e){
            return $this->_getParam($name);
        }catch(UnknownPropertyException $e){
            return $this->_getParam($name);
        }
    }

    public function __set($name, $value){
        try{
            parent::__set($name, $value);
        }catch(InvalidCallException $e){
            $this->_setParam($name, $value);
        }catch(UnknownPropertyException $e){
            $this->_setParam($name, $value);
        }
    }

    private function _setParam($name, $value){
        $this->_params[$name] = $value;
    }

    private function _getParam($name){
        return $this->_params[$name] ?? null;
    }
}
