<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/7/24
 * Time: 上午11:22
 */

namespace business\models\parts;

use common\components\handler\PromoterHandler;
use common\ActiveRecord\PartnerPromoterAR;
use yii\base\InvalidConfigException;
use yii\base\Object;

class Promoter extends Object
{

    public $id;
    protected $AR;

    public function init(){
        if (! $this->AR = PartnerPromoterAR::findOne($this->id)) throw new InvalidConfigException();
    }

    public function getAR(){
        return $this->AR;
    }
    /**
     *====================================================
     * 获取备注信息
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public function getTitle(){
        return $this->AR->title;
    }

    /**
     *====================================================
     * 修改备注
     * @param $title
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public function setTitle($title){
        $this->AR->title = $title;
        return $this->AR->update();
    }

    /**
     *====================================================
     * 修改显示
     * @param $available
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public function setAvailable($available){
        $this->AR->is_available = $available;
        return $this->AR->update();
    }

    /**
     *====================================================
     * 获取邀请人信息
     * @return Account
     * @author shuang.li
     *====================================================
     */
    public function getAccount(){
        return new Account(['id'=>$this->AR->business_user_id]);
    }

    /**
     *====================================================
     * 获取当前二维码邀请纪录
     * @param $currentPage
     * @param $pageSize
     * @param $where
     * @param $status
     * @return object
     * @author shuang.li
     *====================================================
     */
    public function getInviteLog($currentPage,$pageSize,$where = '',$status){
        $where = ' p.partner_promoter_id ='.$this->AR->id.' and p.pay_unixtime>0 '.$where ;
        return PromoterHandler::inviteLogList($currentPage,$pageSize,$where,$status);
    }
}