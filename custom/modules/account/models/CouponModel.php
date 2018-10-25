<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/28
 * Time: 17:34
 */

namespace custom\modules\account\models;


use common\ActiveRecord\CouponRecordAR;
use common\components\handler\coupon\CouponRecordHandler;
use common\components\handler\Handler;
use common\models\Model;
use common\models\parts\coupon\Coupon;
use common\models\parts\coupon\CouponRecord;
use common\models\parts\custom\CustomUser;
use custom\models\parts\ItemInCart;
use custom\models\parts\UrlParamCrypt;
use Yii;
class CouponModel extends  Model
{


    const SCE_GET_TICKET_INFO="get_ticket_info";//获取验证码信息
    const SCE_VALIDATE_TICKET="validate_ticket";//验证激活优惠券
    const SCE_GET_TICKET_LIST="get_ticket_list";//获取优惠券列表


    public $page_size;
    public $current_page;
    public $code;
    public $password;
    public $status;
    public $q;

    public function scenarios()
    {
        return [
            self::SCE_GET_TICKET_LIST=>['current_page','page_size','status'],
            self::SCE_GET_TICKET_INFO=>['code','password'],
            self::SCE_VALIDATE_TICKET=>['code','password'],
        ];
    }

    public function rules()
    {
        return [
            [
                ['current_page'],
                'default',
                'value'=>1
            ],
            [
                ['page_size'],
                'default',
                'value'=>10,
            ],
            [
                ['code','password','page_size','current_page'],
                'required',
                'message'=>9001,
            ],
            [
                ['status'],
                'in',
                'range'=>[CouponRecord::STATUS_ACTIVE,CouponRecord::STATUS_USED,CouponRecord::STATUS_EXPIRE],
                'message'=>3296,
            ],
            [
                ['code'],
                'exist',
                'targetClass'=>CouponRecordAR::className(),
                'targetAttribute'=>['code'=>'code'],
                'message'=>3297,
            ],
            [
                ['password'],
                'common\validators\coupon\CouponPasswordValidator',
                'code'=>$this->code,
                'customerId'=>$this->getCustomerId(),//验证用户
                'messageNotExists'=>3294,
                'message'=>3292,
            ],
        ];
    }

    //获取状态
    protected function getRecordStatus(){
        return CouponRecord::STATUS_EXCITED;
    }

    //获取当前用户ID
    protected function getCustomerId(){
        return Yii::$app->user->id;
    }


    private function resetResult($record){
        return  Handler::getMultiAttributes($record,[
            'id',
            'code',
            'expire'=>'Expire',
            'customer'=>'Customer',
            'status',
            'statusTxt'=>'statusTxt',
            'active_time'=>'activeTime',
            'used_time'=>'usedTime',
            'create_time'=>'createTime',
            'Coupon'=>'Coupon',
            '_func'=>[
                'Coupon'=>function($item){
                    $supplier=$item->getSupplier();
                    return [
                        'id'=>$item->id,
                        'name'=>$item->name,
                        'price'=>$item->price,
                        'consume_limit'=>$item->total_limit,
                        'consume_limit_type'=>$item->total_limit,
                        'receive_limit'=>$item->receive_limit,
                        'total_quantity'=>$item->total_quantity,
                        'send_quantity'=>$item->send_quantity,
                        'start_time'=>$item->getStartTime(),
                        'end_time'=>$item->getEndTime(),
                        'supplier'=>($supplier?[
                            'id'=>$supplier->id,
                            'realName'=>$supplier->getRealName(),
                            'company_name'=>$supplier->getCompanyName(),
                            'brand_name'=>$supplier->getBrandName(),
                            'mobile'=>$supplier->getMobile(),
                            'telephone'=>$supplier->getTelephone(),
                            'address'=>$supplier->getAddress(),
                            'areaCode'=>$supplier->getAreaCode(),

                        ]:false),
                    ];

                }
            ],
        ]);

    }

    //获取优惠券列表
    public function getTicketList(){
        $model = CouponRecordHandler::search($this->page_size,$this->current_page,null,new CustomUser(['id'=>$this->getCustomerId()]),$this->status,['id' => SORT_DESC]);
        $data = [];
        foreach($model->models as $key=>$var){
            $record = new CouponRecord(['id'=>$var['id']]);
            $data[] = $this->resetResult($record);
        }
        return [
            'count' => $model->count,
            'total_count' => $model->totalCount,
            'codes' => $data,
        ];
    }

    //激活优惠券
    public function validateTicket(){
        $record=new CouponRecord(['ticket_code'=>$this->code,'passwd'=>$this->password]);
        if($record->setActive(new CustomUser(['id'=>$this->getCustomerId()]))){
            return true;
        }
        $this->addError('validateTicket',3293);
        return false;
    }

    //获取优惠券信息
    public function getTicketInfo(){
        $record=new CouponRecord(['ticket_code'=>$this->code,'passwd'=>$this->password]);
        return $this->resetResult($record);
    }
}
