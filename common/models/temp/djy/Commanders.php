<?php
namespace common\models\temp\djy;

use Yii;
use yii\base\Object;
use common\ActiveRecord\CustomUserAR;

class Commanders extends Object{

    const CACHE_EXPIRE = 86400;
    const CACHE_ACCOUNTS = 'commanders_account';
    const CACHE_QUATERNARY = 'commanders_quaternary';
    const CACHE_QUATERNARY_COMMANDER = 'commanders_quaternary_unique';
    const CACHE_TOP = 'commanders_top';

    private $_accounts = false;
    private $_topAreas = false;
    private $_quaternaryAreas = false;
    private $_quaternaryCommander = false;

    public function init(){
        if($accounts = Yii::$app->cache->get(self::CACHE_ACCOUNTS)){
            $this->_accounts = $accounts;
            $this->getTopAreaList();
            $this->getQuaternaryAreaList();
            $this->getQuaternaryAreaCommander();
        }else{
            $configFile = dirname(dirname(dirname(dirname(__DIR__)))) . '/custom/models/parts/temp/UserProductOrderLimit/user_account.php';
            if(is_readable($configFile)){
                if($accounts = include($configFile)){
                    Yii::$app->cache->set(self::CACHE_ACCOUNTS, $accounts, self::CACHE_EXPIRE);
                    $this->_accounts = $accounts;
                }
            }
        }
    }

    public function getAccounts(){
        return $this->_accounts;
    }

    public function getTopAreaList(){
        if($list = Yii::$app->cache->get(self::CACHE_TOP)){
            $this->_topAreas = $list;
        }else{
            $this->_topAreas = CustomUserAR::find()->
                select(['business_top_area_id'])->
                distinct()->
                where(['account' => $this->getAccounts()])->
                column();
            Yii::$app->cache->set(self::CACHE_TOP, $this->_topAreas, self::CACHE_EXPIRE);
        }
        return $this->_topAreas;
    }

    public function getQuaternaryAreaList(){
        if($list = Yii::$app->cache->get(self::CACHE_QUATERNARY)){
            $this->_quaternaryAreas = $list;
        }else{
            $this->_quaternaryAreas = CustomUserAR::find()->
                select(['business_quaternary_area_id'])->
                distinct()->
                where(['account' => $this->getAccounts()])->
                column();
            Yii::$app->cache->set(self::CACHE_QUATERNARY, $this->_quaternaryAreas, self::CACHE_EXPIRE);
        }
        return $this->_quaternaryAreas;
    }

    public function getQuaternaryAreaCommander(){
        if($list = Yii::$app->cache->get(self::CACHE_QUATERNARY_COMMANDER)){
            $this->_quaternaryCommander = $list;
        }else{
            $accounts = implode(',', $this->getAccounts());
            $result = Yii::$app->db->createCommand("SELECT DISTINCT [[business_quaternary_area_id]] AS `quaternary_area_id`, [[account]] AS `account` FROM {{%custom_user}} WHERE [[account]] IN ({$accounts})")->queryAll();
            $this->_quaternaryCommander = $result ? array_column($result, 'account', 'quaternary_area_id') : [];
            Yii::$app->cache->set(self::CACHE_QUATERNARY_COMMANDER, $this->_quaternaryCommander, self::CACHE_EXPIRE);
        }
        return $this->_quaternaryCommander;
    }

    public function isValid(){
        return (bool)$this->_accounts;
    }

    public function reset(){
        Yii::$app->cache->delete(self::CACHE_ACCOUNTS);
        Yii::$app->cache->delete(self::CACHE_TOP);
        Yii::$app->cache->delete(self::CACHE_QUATERNARY);
        Yii::$app->cache->delete(self::CACHE_QUATERNARY_COMMANDER);
        $this->init();
    }
}
