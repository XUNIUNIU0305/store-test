<?php
namespace business\models\parts;

use Yii;
use yii\base\Object;
use common\ActiveRecord\BusinessUserAR;
use yii\base\InvalidConfigException;
use common\components\handler\PromoterHandler;
use common\ActiveRecord\PartnerPromoterAR;
use business\models\parts\trade\Wallet;

class Account extends Object{

    const STATUS_NORMAL = 0;
    const STATUS_REMOVE = 1;
    const STATUS_UNREGISTERED = 2;

    public $id;
    public $account;
    public $mobile;

    private $_wallet;

    protected $AR;

    public function init(){
        if($this->id){
            $userCondition = $this->id;
        }elseif($this->account){
            $userCondition = ['account' => $this->account];
        }elseif($this->mobile){
            $userCondition = ['mobile' => $this->mobile];
        }else{
            $userCondition = false;
        }
        if($userCondition){
            if(!$this->AR = BusinessUserAR::findOne($userCondition))throw new InvalidConfigException('unknow user');
            $this->id = $this->AR->id;
            $this->account = $this->AR->account;
            $this->mobile = $this->getMobile();
        }else{
            throw new InvalidConfigException('unavailable user');
        }
    }

    public function getWallet(){
        if(is_null($this->_wallet)){
            $this->_wallet = new Wallet(['userId' => $this->id]);
        }
        return $this->_wallet;
    }

    public function register(string $passwd, int $mobile, $return = 'throw'){
        return Yii::$app->RQ->AR($this->AR)->update([
            'passwd' => Yii::$app->security->generatePasswordHash($passwd),
            'mobile' => $mobile,
            'status' => self::STATUS_NORMAL,
            'register_time' => Yii::$app->time->fullDate,
            'register_unixtime' => Yii::$app->time->unixTime,
        ], $return);
    }

    public function getAccount(){
        return $this->AR->account;
    }

    public function getName(){
        return $this->AR->name;
    }

    public function setName(string $name, $return = 'throw'){
        if(empty($name))return Yii::$app->EC->callback($return, 'string');
        return Yii::$app->RQ->AR($this->AR)->update([
            'name' => $name,
        ], $return);
    }

    public function getMobile(){
        return $this->AR->mobile ? : '';
    }

    public function getRemark(){
        return $this->AR->remark;
    }

    public function setRemark(string $remark, $return = 'throw'){
        return Yii::$app->RQ->AR($this->AR)->update([
            'remark' => $remark,
        ], $return);
    }

    public function getRole(){
        return new Role(['id' => $this->AR->business_role_id]);
    }

    public function setRole(Role $role, Area $area, $return = 'throw'){
        return Yii::$app->RQ->AR($this->AR)->update([
            'business_role_id' => $role->role,
            'business_area_id' => $area->id,
            'top_business_area_id' => $area->topArea->id,
            'level' => $role->level,
        ], $return);
    }

    public function getArea(){
        return new Area(['id' => $this->AR->business_area_id]);
    }

    public function getTopArea(){
        return new Area(['id' => $this->AR->top_business_area_id]);
    }

    public function resetRole($return = 'throw'){
        if(in_array($this->getRole()->role, [Role::SUPER_ADMIN, Role::ADMIN])){
            return $this->setRole(new Role(['id' => Role::UNDEFINED]), new Area(['id' => Area::LEVEL_UNDEFINED]), $return);
        }else{
            return $this->getArea()->removeUser($this, $return);
        }
    }

    public function getLevel(){
        return $this->AR->level;
    }

    public function getStatus(){
        return $this->AR->status;
    }

    public function setStatus(int $status, $return = 'throw'){
        switch($this->getStatus()){
            case self::STATUS_NORMAL:
                $availableStatus = [
                    self::STATUS_REMOVE,
                ];
                break;

            case self::STATUS_REMOVE:
                $availableStatus = [];
                break;

            case self::STATUS_UNREGISTERED:
                $availableStatus = [
                    self::STATUS_NORMAL,
                    self::STATUS_REMOVE,
                ];
                break;

            default:
                $availableStatus = [];
                break;
        }
        if(!in_array($status, $availableStatus))return Yii::$app->EC->callback($return, 'unavailable status');
        switch($status){
            case self::STATUS_NORMAL:
                $actionTime = [
                    'register_time' => Yii::$app->time->fullDate,
                    'register_unixtime' => Yii::$app->time->unixTime,
                ];
                break;

            case self::STATUS_REMOVE:
                $actionTime = [
                    'remove_time' => Yii::$app->time->fullDate,
                    'remove_unixtime' => Yii::$app->time->unixTime,
                ];
                break;

            default:
                $actionTime = [];
                break;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if($status == self::STATUS_REMOVE){
                $this->resetRole();
            }
            Yii::$app->RQ->AR($this->AR)->update(array_merge([
                'status' => $status,
            ], $actionTime));
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }

    public function resetPassword(string $passwd, bool $confirm = false, $return = 'throw'){
        if(!$confirm)return Yii::$app->EC->callback($return, 'you must confirm before reset the password');
        if(empty($passwd))return Yii::$app->EC->callback($return, 'string');
        return Yii::$app->RQ->AR($this->AR)->update([
            'passwd' => Yii::$app->security->generatePasswordHash($passwd),
        ], $return);
    }

    /**
     *====================================================
     * 获取该用户生成的二维码
     * @param $currentPage
     * @param $pageSize
     * @return \yii\data\ActiveDataProvider
     * @author shuang.li
     *====================================================
     */
    public function getQrcode($currentPage,$pageSize){
        return PromoterHandler::providesQrcode($currentPage,$pageSize,['id'=>$this->getPromoterId()]);
    }

    /**
     *====================================================
     * 获取该账户下的流水纪录
     * @param $currentPage
     * @param $pageSize
     * @param $status
     * @param $where
     * @return \yii\data\ActiveDataProvider
     * @author shuang.li
     *====================================================
     */
    public function getStreamLog($currentPage,$pageSize,$where,$status){
        $promoterId = implode(',',$this->getPromoterId());
        $str = empty($promoterId) ? 'p.partner_promoter_id =0 ' :"p.partner_promoter_id in ({$promoterId}) ";
        $where =  $str.' and p.pay_unixtime>0 '.$where;
        return PromoterHandler::inviteLogList($currentPage,$pageSize,$where,$status);
    }

    /**
     *====================================================
     * 获取当前用户可利用的所有邀请码id
     * @author shuang.li
     * @return array
     *====================================================
     */
    public function getPromoterId(){
       return  Yii::$app->RQ->AR(new PartnerPromoterAR())->column([
            'select'=>['id'],
            'where'=>[
                'business_user_id'=>$this->id,
            ]
        ]);
    }
}
