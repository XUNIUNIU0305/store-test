<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/22
 * Time: 18:02
 */

namespace common\models\parts\custom;

use common\ActiveRecord\CouponAR;
use common\ActiveRecord\CouponRecordAR;
use common\ActiveRecord\CustomUserAddressAR;
use common\ActiveRecord\CustomUserAR;
use common\ActiveRecord\CustomUserWalletAR;
use common\components\handler\CustomUserTechnicianHandler;
use common\ActiveRecord\OrderAR;
use common\ActiveRecord\WechatUserBindAR;
use common\components\handler\coupon\CouponRecordHandler;
use common\models\parts\Address;
use common\models\parts\business_area\QuaternaryArea;
use common\models\parts\business_area\SecondaryArea;
use common\models\parts\business_area\TertiaryArea;
use common\models\parts\business_area\TopArea;
use common\models\parts\coupon\Coupon;
use common\models\parts\coupon\CouponRecord;
use common\models\parts\district\City;
use common\models\parts\district\District;
use common\models\parts\district\Province;
use common\models\parts\wechat\WechatUser;
use custom\models\parts\wechat\Wechat;
use yii\base\InvalidCallException;
use yii\base\Object;
use Yii;
use yii\data\ActiveDataProvider;
use business\models\parts\Area;
use custom\models\parts\trade\Wallet;
use common\ActiveRecord\BusinessAreaAR;

class CustomUser extends Object{


    public $id;
    public $mobile;
    public $mobile_bak;
    public $account;
    protected $AR;
    private $_wallet;

    const STATUS_NORMAL = 0;
    const STATUS_FORBIDDEN = 1;

    const LEVEL_PARTNER = 2;
    const LEVEL_IN_SYSTEM = 3;
    const LEVEL_COMPANY = 4;
    const AUTHORIZED = 1;
    const UNAUTHORIZED = 0;

    public function init(){
        if ($this->id) {
            if (!$this->AR = CustomUserAR::findOne($this->id)) throw new InvalidCallException;
        } elseif ($this->mobile) {
            if (!$this->AR = CustomUserAR::findOne(['mobile' => $this->mobile])) throw new InvalidCallException;
        } elseif($this->account){
            if(!$this->AR = CustomUserAR::findOne(['account' => $this->account])) throw new InvalidCallException;
        } else {
            throw new InvalidCallException;
        }
        $this->id = $this->AR->id;
        $this->mobile=$this->AR->mobile;
        $this->account=$this->AR->account;
    }

    public function getAccount(){
        return $this->AR->account;
    }

    public function getArea(){
        return new Area(['id' => $this->AR->business_area_id]);
    }

    public function getLevel(){
        return $this->AR->level;
    }

    public static function getLevels(){
        return [
            self::LEVEL_PARTNER,
            self::LEVEL_IN_SYSTEM,
            self::LEVEL_COMPANY,
        ];
    }

    public static $levelLabels = [
        self::LEVEL_PARTNER => '加盟店',
        self::LEVEL_IN_SYSTEM => '体系店',
        self::LEVEL_COMPANY => '运营商店'
    ];

    public function getWallet(){
        if(is_null($this->_wallet)){
            $this->_wallet = new Wallet([
                'userId' => $this->AR->id,
            ]);
        }
        return $this->_wallet;
    }

    public function getIsAuthorized(){
        return $this->AR->authorized ? true : false;
    }

    public function setAuthorized(int $validSeconds, $return = 'throw'){
        if($this->getIsAuthorized())return Yii::$app->EC->callback($return, 'this user has been authorized');
        $expireUnixTime = Yii::$app->time->unixTime + $validSeconds;
        $expireDateTime = date('Y-m-d H:i:s', $expireUnixTime);
        return Yii::$app->RQ->AR($this->AR)->update([
            'authorized' => self::AUTHORIZED,
            'expire_datetime' => $expireDateTime,
            'expire_unixtime' => $expireUnixTime,
        ], $return);
    }

    public function getIsExpired(){
        return (Yii::$app->time->unixTime > $this->AR->expire_unixtime);
    }

    public function getStatus(){
        return $this->AR->status;
    }

    //获取顶级区域
    public function getTopArea()
    {
        try{
            return new TopArea(['topId' => $this->AR->business_top_area_id]);
        }catch (\Exception $e){
            return false;
        }

    }

    //获取二级区域
    public function getSecondaryArea()
    {
        try{
            return new SecondaryArea(['secondaryId' => $this->AR->business_secondary_area_id]);
        }catch (\Exception $e){
            return false;
        }

    }

    //获取三级
    public function getTertiaryArea()
    {
        try{
            return new TertiaryArea(['tertiaryId' => $this->AR->business_tertiary_area_id]);
        }catch (\Exception $e){
            return false;
        }

    }

    //获取四级
    public function getQuaternayArea()
    {
        try{
            return new QuaternaryArea(['quaternaryId' => $this->AR->business_quaternary_area_id]);
        }catch (\Exception $e){
            return false;
        }

    }

    //获取省份
    public function getProvince()
    {
        try{
            return new Province(['provinceId' => $this->AR->district_province_id]);
        }catch (\Exception $e){
            return false;
        }

    }

    //获取城市
    public function getCity()
    {
        try{
            return new City(['cityId' => $this->AR->district_city_id]);
        }catch (\Exception $e){
            return false;
        }

    }

    //获取区域
    public function getDistrict()
    {
        try{
            return new District(['districtId' => $this->AR->district_district_id]);
        }catch (\Exception $e){
            return false;
        }

    }


    //获取当前用户手机号码
    public function getMobile()
    {
        return $this->AR->mobile;
    }

    //获取当前用户手机号码
    public function getMobileBak()
    {
        return $this->AR->mobile_bak;
    }

    //获取邮箱
    public function getEmail()
    {
        return $this->AR->email;
    }

    //获取图像
    public function getHeaderImg()
    {
        return trim($this->AR->header_img);
    }

    //获取店铺名
    public function getShopName()
    {
        return $this->AR->shop_name;
    }

    //获取昵称
    public function getNickName()
    {
        return $this->AR->nick_name;
    }

    public function getInvoiceTitle(){
        return $this->AR->invoice_title;
    }

    public function getInvoiceNumber(){
        return $this->AR->invoice_number;
    }

    //获取下级技师列表
    public function getTechnician(){
        return array_map(function($item){
            return [
                'id'=>$item->id,
                'name'=>$item->getName(),
            ];
        },CustomUserTechnicianHandler::getList($this));
    }

    //批量更新用户资料信息
    public function setUserAttr(array $attr = [], $return = "throw")
    {
        return Yii::$app->RQ->AR($this->AR)->update($attr, $return);
    }


    //更改密码
    public function setPassword($password, $return = "throw")
    {
        return Yii::$app->RQ->AR($this->AR)->update(['passwd' => Yii::$app->security->generatePasswordHash($password)], $return);
    }

    //更换手机号码
    public function setMobile($mobile, $return = "throw")
    {
        return Yii::$app->RQ->AR($this->AR)->update(['mobile' => $mobile], $return);
    }


    //获取用户领取优惠券数量
    public function getCouponQuantity(Coupon $coupon){
        return CouponRecordAR::find()->where(['coupon_id'=>$coupon->id,'custom_user_id'=>$this->id])->count();
    }

    //获取用户佣有的优惠券
    public function getCoupons($pageSize,$currentPage,$status=null,$orderBy=['id'=>SORT_DESC]){
        return CouponRecordHandler::search($pageSize,$currentPage,null,$this,$status,$orderBy);
    }

    //获取可使用优惠券
    public function getAvailableTickets(bool $obj = true){
        $tickets = Yii::$app->RQ->AR(new CouponRecordAR)->all([
            'select' => ['id', 'coupon_id'],
            'where' => [
                'custom_user_id' => $this->id,
                'status' => CouponRecord::STATUS_ACTIVE,
            ],
        ], []);
        if(!$tickets)return [];
        $couponIds = array_unique(array_column($tickets, 'coupon_id'));
        if(!$couponIds)return [];
        $availableCouponIds = Yii::$app->RQ->AR(new CouponAR)->column([
            'select' => ['id'],
            'where' => [
                'id' => $couponIds,
            ],
            'andWhere' => [
                '<=', 'start_time', time(),
            ],
        ]);
        $tickets = array_column(array_filter($tickets, function($ticket)use($availableCouponIds){
            return in_array($ticket['coupon_id'], $availableCouponIds);
        }), 'id');
        if($obj){
            return array_map(function($ticket){
                return new CouponRecord([
                    'id' => $ticket,
                ]);
            }, $tickets);
        }else{
            return $tickets;
        }
    }

    //获取用户拥有的订单id
    public function getOrders($currentPage,$pageSize){

        return new ActiveDataProvider([
            'query' => OrderAR::find()->select('id')->where(['and','custom_user_id = '.$this->id,'status <> 0','pay_unixtime <> 0'])->asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => [
                    'create_datetime'=>SORT_DESC,
                ],
            ],
        ]);
    }

    //获取custom站 绑定微信用户名称
    public function getWechatUserName($status = 1){
        $wechatUserId = Yii::$app->RQ->AR(new WechatUserBindAR())->scalar(['select' => ['wechat_user_id'], 'where' => ['site' => $status, 'user_id' => $this->id]]);
        return empty($wechatUserId) ? '' : (new WechatUser(['id'=>$wechatUserId]))->nickName;

    }

    //获取custom站 绑定微信用户名称
    public function getWechatUser($status = 1){
        $wechatUserId = Yii::$app->RQ->AR(new WechatUserBindAR())->scalar(['select' => ['wechat_user_id'], 'where' => ['site' => $status, 'user_id' => $this->id]]);
        return empty($wechatUserId) ? '' : new WechatUser(['id'=>$wechatUserId]);

    }

    /**
     *====================================================
     * 获取用户默认地址
     * @return Address
     * @author shuang.li
     *====================================================
     */
    public function getDefaultAddress(){
        $addressId = Yii::$app->RQ->AR(new CustomUserAddressAR())->scalar([
            'select'=>['id'],
            'where'=>[
                'custom_user_id'=>$this->AR->id,
                'default'=>CustomUserAddressAR::DEFAULT_ADDRESS,
            ]
        ]);
        try {
            return new Address(['id'=>$addressId]);
        }catch (\Exception $exception){
            return false;
        }
    }

    public function getIsAvailable(){
        return ($this->AR->status == self::STATUS_NORMAL && !$this->getIsExpired());
    }

    /**
     *====================================================
     * 修改状态
     * @param string $return
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public function setStatus($return = "throw")
    {
        return Yii::$app->RQ->AR($this->AR)->update(['status' =>self::STATUS_FORBIDDEN ], $return);
    }

    /**
     *====================================================
     * 获取账户余额
     * @return mixed
     * @author forrestgao
     *====================================================
     */
    public function getCustomUserBalance()
    {
        return CustomUserWalletAR::find()->select(['rmb'])->where(['custom_user_id' => $this->id])->scalar();
    }

    // 设置备份手机号
    public function setMobileBak($mobileBak, $return = "throw")
    {
        return Yii::$app->RQ->AR($this->AR)->update(['mobile_bak' => $mobileBak], $return);
    }

    // 解绑用户的微信号
    public function unbindUserWechat()
    {
        $wechatUserBind = WechatUserBindAR::find()->where([
            'user_id' => $this->id,
            'site' => Wechat::SITE_CUSTOM,
        ])->one();
        if (!is_null($wechatUserBind)) {
            if ($wechatUserBind->delete()) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function getBusinessTopAreaId(){
        return $this->AR->business_top_area_id;
    }

    public function getBusinessSecondaryAreaId(){
        return $this->AR->business_secondary_area_id;
    }

    public function getBusinessTertiaryAreaId(){
        return $this->AR->business_tertiary_area_id;
    }
    public function getBusinessQuaternaryAreaId(){
        return $this->AR->business_quaternary_area_id;
    }


    /**
     * 获取所属区域信息
     * @param array $id
     * @return array
     */
    public function getBusinessArea(array $id)
    {

        $area_res = [];
        $res = BusinessAreaAR::find()->select(['level','name'])->where(['in','id',$id])->asArray()->all();
        if($res){
            foreach ($res as $value){
                $area_res[$value['level']] = $value['name'];
            }
        }
        return $area_res;
    }

}
