<?php
namespace common\models\parts\trade\recharge\nanjing\account;

use Yii;
use yii\base\Object;
use yii\db\ActiveRecord;
use yii\base\InvalidConfigException;

abstract class AccountAbstract extends Object{

    public $id;
    private $_userAccount;

    const ACCOUNT_TYPE_CUSTOM = 1;
    const ACCOUNT_TYPE_SUPPLY = 2;
    const ACCOUNT_TYPE_BUSINESS = 3;
    const ACCOUNT_TYPE_ADMIN = 4;

    public static function getAccountTypes(){
        return [
            self::ACCOUNT_TYPE_CUSTOM,
            self::ACCOUNT_TYPE_ADMIN,
            self::ACCOUNT_TYPE_SUPPLY,
            self::ACCOUNT_TYPE_BUSINESS,
        ];
    }

    public function init(){
        $this->_userAccount = Yii::$app->RQ->AR($this->getActiveRecord())->scalar([
            'select' => [$this->getAccountField()],
            'where' => ['id' => $this->id],
            'limit' => 1,
        ]);
        if(!$this->_userAccount)throw new InvalidConfigException('unavailable user account');
        if(!in_array(static::getUserType(), self::getAccountTypes()))throw new InvalidConfigException('unavailable user type');
    }

    public function getMerUserId(){
        return ($this->getUserType() . $this->_userAccount);
    }

    public function getUserAccount(){
        return $this->_userAccount;
    }

    public function getNanjingAccount($return = 'throw'){
        return NanjingAccount::newInstance($this->getUserAccount(), static::getUserType(), $return);
    }

    public function getMobilePhone() : string{
        return '';
    }

    /**
     * 账户数据表
     */
    abstract public function getActiveRecord() : ActiveRecord;

    /**
     * 账户数据表字段
     */
    abstract public function getAccountField() : string;

    /**
     * 账户类型
     */
    abstract public function getUserType() : int;
}
