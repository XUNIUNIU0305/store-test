<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/28
 * Time: 10:14
 */

namespace admin\modules\activity\models;



use common\ActiveRecord\CouponAR;
use common\ActiveRecord\CouponRecordAR;
use common\ActiveRecord\CustomUserAR;
use common\ActiveRecord\SupplyUserAR;
use common\components\amqp\Message;
use common\components\handler\coupon\CouponHandler;
use common\components\handler\coupon\CouponRecordHandler;
use common\components\handler\coupon\CouponRuleHandler;
use common\components\handler\Handler;
use common\models\Model;
use common\models\parts\coupon\Coupon;
use common\models\parts\coupon\CouponRecord;
use common\models\parts\coupon\CouponRule;
use common\models\parts\custom\CustomUser;
use common\models\parts\supply\SupplyUser;
use console\models\AmqpTask\CouponAmqpTask;
use yii\base\Exception;
use Yii;
use yii\web\NotFoundHttpException;

class CouponModel extends Model
{

    const SCE_CREATE_COUPON='create_coupon';//创建保存优惠券信息
    const SCE_GET_COUPON_LIST="get_coupon_list";//查询搜索优惠券
    const SCE_GET_COUPON_INFO="get_coupon_info";//获取优惠券详情
    const SCE_DELETE_COUPON="delete_coupon";//删除优惠券信息

    //获取分发记录信息及相关操作
    const SCE_GET_TICKET_LIST="get_ticket_list";//获取优惠券分发列表
    const SCE_SEND_TICKET_TO_PERSON="send_ticket_to_person";//发送优惠券到个人
    const SCE_CREATE_TICKET="create_ticket";//创建实体券
    const SCE_CREATE_RULE="create_rule";//创建自动发放规则
    const SCE_ADD_QUANTITY="add_quantity";//新增发行量
    const SCE_CANCEL_TICKET="cancel_ticket";//注销优惠券


    //导出文件
    const SCE_EXPORT="export_data";//导出



    public $page_size;
    public $current_page;
    public $status;//优惠券状态
    public $name;//优惠券名称

    public $price;//面值
    public $total_limit;//使用消费限额
    public $receive_limit;//单人限领数量
    public $total_quantity;//总发行量
    public $start_time;//开始时间
    public $end_time;//结束时间
    public $supply_user_id;//适合商户ID
    public $money_limit_type;//消费金额限制类型 0表示订单总额，1表示商户消费金额

    public $id;
    public $custom_code;//用户编号
    public $ticket_status;
    public $quantity;//数量

    public $money_limit;
    public $post_limit;
    public $suppliers;
    public $coupon_id;

    public $ticket_id;
    //允许使用的状态值
    private $_status=[
        Coupon::STATUS_FINISHED,
        Coupon::STATUS_NORMAL,
        Coupon::STATUS_STOP,
        Coupon::STATUS_DEL,
    ];

    //分发的优惠券状态
    private $_ticket_status=[
        CouponRecord::STATUS_EXCITED,
        CouponRecord::STATUS_USED,
        CouponRecord::STATUS_EXPIRE,
        CouponRecord::STATUS_CANCEL,
        CouponRecord::STATUS_ACTIVE
    ];

    //限制消费金额类型取值范围
    private  $_moneyLimitType=[
        CouponRule::MONEY_LIMIT_TYPE_SUPPLY,
        CouponRule::MONEY_LIMIT_TYPE_TOTAL,
    ];


    //配置场景
    public function scenarios()
    {
        return [
            self::SCE_CREATE_COUPON=>['name','price','total_limit','receive_limit','total_quantity','start_time','end_time','supply_user_id'],
            self::SCE_GET_COUPON_LIST=>['page_size','current_page','status','name'],
            self::SCE_GET_COUPON_INFO=>['id'],
            self::SCE_DELETE_COUPON=>['id'],
            self::SCE_GET_TICKET_LIST=>['id','page_size','current_page','ticket_status'],
            self::SCE_SEND_TICKET_TO_PERSON=>['id','custom_code'],
            self::SCE_CREATE_TICKET=>['id','quantity'],
            self::SCE_CREATE_RULE=>['id','start_time','end_time','money_limit','post_limit','suppliers','money_limit_type'],
            self::SCE_ADD_QUANTITY=>['id','quantity'],
            self::SCE_CANCEL_TICKET=>['id','ticket_id'],
            self::SCE_EXPORT=>['coupon_id'],

        ];
    }


    public function rules()
    {
        return [
            [
                ['id','coupon_id','custom_code','quantity','ticket_status','money_limit','post_limit','ticket_id'],
                'required',
                'message'=>9001,
               // 'on'=>[self::SCE_SEND_TICKET_TO_PERSON],
            ],
            [
                ['coupon_id'],
                'exist',
                'targetClass'=>CouponAR::className(),
                'targetAttribute'=>['coupon_id'=>'id'],
                'message'=>5228,
            ],
            [
                ['id'],
                'exist',
                'targetClass'=>CouponAR::className(),
                'targetAttribute'=>['id'=>'id'],
                'message'=>5228,
            ],
            [
                ['ticket_id'],
                'each',
                'rule'=>[
                    'exist',
                    'targetClass'=>CouponRecordAR::className(),
                    'targetAttribute'=>['ticket_id'=>'id'],
                    'message'=>5228,
                ],
            ],
            [
                ['money_limit'],
                'number',
                'min'=>0,
                'max'=>9999999,
                'tooBig'=>5236,
                'tooSmall'=>5236,
                'message'=>5236,
            ],
            [
                ['post_limit'],
                'in',
                'range'=>[CouponRule::LIMIT_SEND_YES,CouponRule::LIMIT_SEND_NO],
                'message'=>5235,
            ],
            [
                ['money_limit_type'],
                'in',
                'range'=>$this->_moneyLimitType,
                'message'=>5239,
            ],
            [
                ['custom_code'],
                'each',
                'rule'=>[
                    'exist',
                    'targetClass'=>CustomUserAR::className(),
                    'targetAttribute'=>['custom_code'=>'account'],
                    'message'=>5245,
                ],

            ],
            [
                ['suppliers'],
                'each',
                'rule'=>[
                    'exist',
                    'targetClass'=>SupplyUserAR::className(),
                    'targetAttribute'=>['suppliers'=>'id'],
                    'message'=>5238,
                ],
            ],

            [
                ['supply_user_id'],
                'default',
                'value'=>0,
            ],
            [
                ['start_time'],
                'default',
                'value'=>date("Y-m-d H:i:s")
            ],
            [
                ['end_time'],
                'default',
                'value'=>date("Y-m-d H:i:s"),
            ],
            [
                ['name','price','total_limit','receive_limit','total_quantity','start_time','end_time','supply_user_id'],
                'required',
                'message'=>9001,
                'on'=>[self::SCE_CREATE_COUPON],
            ],
            /*
            [
                ['start_time','end_time'],
                'date',
                'format'=>"Y-m-d H:i:s",
                'message'=>5226,
            ],*/
            [
                ['end_time'],
                'common\validators\coupon\EndTimeValidator',
                'startTime'=>$this->start_time,
                'message'=>5242,
                'messageOutRange'=>5243,
            ],
            [
                ['supply_user_id'],
                'common\validators\coupon\SupplyUserValidator',
                'message'=>5225
            ],
            [
                ['price'],
                'number',
                'min'=>0.01,
                'max'=>9999999,
                'tooSmall'=>5221,
                'tooBig'=>5221,
                'message'=>5221,
            ],
            [
                ['total_limit'],
                'number',
                'min'=>$this->price+1,
                'max'=>10000000,
                'tooSmall'=>5222,
                'tooBig'=>5222,
                'message'=>5222,
            ],
            [
                ['receive_limit'],
                'number',
                'min'=>0,
                'max'=>$this->total_quantity,
                'tooBig'=>5223,
                'tooSmall'=>5223,
                'message'=>5223
            ],
            [
                ['total_quantity'],
                'number',
                'min'=>1,
                'max'=>9999999,
                'tooSmall'=>5224,
                'tooBig'=>5224,
                'message'=>5224,
            ],
            [
                ['ticket_status'],
                'in',
                'range'=>$this->_ticket_status,
                'message'=>5244
            ],
            [
                ['ticket_status'],
                'default',
                'value'=>Coupon::STATUS_NORMAL,
            ],
            [
                ['name'],
                'default',
                'value'=>null,
            ],
            [
                ['page_size'],
                'default',
                'value'=>10,
            ],
            [
                ['current_page'],
                'default',
                'value'=>1,
            ],
            [
                ['status'],
                'default',
                'value'=>null,
            ],
            [
                ['status'],
                'in',
                'range'=>$this->_status,
                'message'=>5220,
            ],
            [
                ['quantity'],
                'number',
                'min'=>1,
                'max'=>$this->getSendQuantity(),
                'tooBig'=>5247,
                'tooSmall'=>5247,
                'message'=>5247,
                'on'=>[self::SCE_CREATE_TICKET],
            ],
            [
                ['quantity'],
                'number',
                'min'=>1,
                'max'=>$this->getMaxQuantity(),
                'tooBig'=>5247,
                'tooSmall'=>5247,
                'message'=>5247,
                'on'=>[self::SCE_ADD_QUANTITY],
            ],

        ];
    }


    //导出
    public function exportData(){
        $coupon=new Coupon(['id'=>$this->coupon_id]);
        //取数据
        $model=CouponRecordHandler::search(CouponRecordHandler::getCouponRecordQuantity(CouponRecord::STATUS_EXCITED),1,$coupon,null,CouponRecord::STATUS_EXCITED);

        $excel=new \PHPExcel();
        $excel->createSheet();//创建内置表
        //设置当前活动sheet
        $excel->setActiveSheetIndex(0);
        $sheet=$excel->getActiveSheet();
        $sheet->setTitle('实体优惠券导出');
        $sheet->setCellValue('A1','ID')
            ->setCellValue('B1','优惠券名称')
            ->setCellValue('C1','优惠券面值')
            ->setCellValue('D1','可使用时间')
            ->setCellValue('E1','序列号')
            ->setCellValue('F1','密码')
            ->setCellValue('G1','生成时间')
            ->setCellValue('H1','可使用门店');

        foreach($model->models as $key=>$var){
            $rs=new CouponRecord(['id'=>$var['id']]);
            $supplier=$coupon->getSupplier();
            $index=$key+2;
            $sheet->setCellValueExplicit('A'.$index,$rs->id,\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('B'.$index,$coupon->name)
                ->setCellValueExplicit('C'.$index,$coupon->price,\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('D'.$index,$coupon->getEndTime(),\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('E'.$index,$rs->code,\PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('F'.$index,$rs->password)
                ->setCellValue('G'.$index,$rs->getCreateTime())
                ->setCellValue('H'.$index,$supplier?$supplier->getCompanyName():"");
        }
        //按照指定格式生成excel文件
        $excelWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-excel");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header("Content-Disposition:attachment;filename=\"实体优惠券.xlsx\"");
        header("Content-Transfer-Encoding:binary");
        $excelWriter->save('php://output');exit();
    }


    //获取最大可发行量
    protected  function getSendQuantity(){
        if(!$this->id)return 0;
        return (new Coupon(['id'=>$this->id]))->getSendQuantity();
    }


    //获取最大可发行量
    protected  function getMaxQuantity(){
        if(!$this->id)return 0;
        return (new Coupon(['id'=>$this->id]))->getMaxQuantity();
    }

    //注销优惠券
    public function cancelTicket(){
        if((new Coupon(['id'=>$this->id]))->cancelTicket($this->ticket_id)){
            return true;
        }
        $this->addError('cancelTicket',5241);
        return false;
    }

    //新增发行量
    public function addQuantity(){
        if((new Coupon(['id'=>$this->id]))->increaseTotal($this->quantity)){
            return true;
        }
        $this->addError("addQuantity",5248);
        return false;
    }
    //创建或者更新优惠券自动发放规则
    public function createRule(){
        $coupon=new Coupon(['id'=>$this->id]);
        if(CouponRuleHandler::create($coupon,strtotime($this->start_time),strtotime($this->end_time),$this->money_limit,$this->post_limit,CouponRule::STATUS_NORMAL,$this->suppliers,$this->money_limit_type)){
            return true;
        }
        $this->addError('crateRule',5237);
        return false;
    }

    //导出优惠券
    public function exportExcel(){
        $model=CouponRecordHandler::search(CouponRecordHandler::getCouponRecordQuantity(CouponRecord::STATUS_EXCITED),1,null,null,CouponRecord::STATUS_EXCITED);
       return array_map(function($item){
           $record=new CouponRecord(['id'=>$item['id']]);
           $coupon=$record->getCoupon();
           $supply=$coupon->getSupplier();
           return [
               'id'=>$record->id,
               'couponName'=>$coupon->name,
               'couponPrice'=>$coupon->price,
               'startTime'=>$coupon->getStartTime(),
               'endTime'=>$coupon->getEndTime(),
               'code'=>$record->code,
               'password'=>$record->passwd,
               'createTime'=>$record->getCreateTime(),
               'supplier'=>$supply?[
                   'id'=>$supply->id,
                   'account'=>$supply->getAccount(),
                   'companyName'=>$supply->getCompanyName(),
                   'brandName'=>$supply->getBrandName(),
               ]:false,
           ];
       },$model->models);
    }


    //创建实体券
    public function createTicket(){
        $coupon=new Coupon(['id'=>$this->id]);
        if(!$coupon->validateStatus()){
            $this->addError('createTicket',5249);
            return false;
        }
        //加入消息队列
        $couponTask=new CouponAmqpTask(['coupon_id'=>$this->id,'quantity'=>$this->quantity]);
        $message=new Message($couponTask);
        Yii::$app->amqp->publish($message);
        return true;
    }

    //给个人发送优惠券
    public function sendTicketToPerson(){
        if((new Coupon(['id'=>$this->id]))->sendForCustomers($this->custom_code)) {
            return true;
        }
         $this->addError('sendTicketToPerson',5246);
         return false;
    }

    //获取优惠券列表
    public function getTicketList(){
        $model=(new Coupon(['id'=>$this->id]))->getRecords($this->page_size,$this->current_page,null,$this->ticket_status);
        $data=array_map(function($item){
           $record=new CouponRecord(['id'=>$item['id']]);
           $coupon=$record->getCoupon();
           $customer=$record->getCustomer();
           return [
               'id'=>$record->id,
               'code'=>$record->code,
               'password'=>$record->password,
               'expire_time'=>$record->getExpire(),
               'status'=>$record->status,
               'statusTxt'=>$record->getStatusTxt(),
               'active_time'=>$record->getActiveTime(),
               'used_time'=>$record->getUsedTime(),
               'create_time'=>$record->getCreateTime(),
               'coupon'=>[
                   'id'=>$coupon->id,
                   'name'=>$coupon->name,
               ],
               'customer'=>$customer?[
                   'id'=>$customer->id,
                   'account'=>$customer->getAccount(),
               ]:false,
           ];
        },$model->models);


        return [
            'count' => $model->count,
            'total_count' => $model->totalCount,
            'codes' => $data,
        ];

    }




    //删除优惠券
    public function deleteCoupon(){
        //更新状态至删除
        if((new Coupon(['id'=>$this->id]))->setStatus(Coupon::STATUS_DEL)){
            return true;
        }
        $this->addError('deleteCoupon',5229);
        return false;
    }

    //获取优惠券详情
    public function getCouponInfo(){
       return Handler::getMultiAttributes(new Coupon(['id'=>$this->id]),[
           'id',
           'name',
           'price',
           'consume_limit'=>'total_limit',
           'receive_limit',
           'total_quantity',
           'send_quantity',
           'start_time'=>'startTime',
           'end_time'=>'endTime',
           'status',
           'statusTxt'=>'statusTxt',
           'supplier',
           'rule',
           '_func'=>[
               'supplier'=>function($supplier){
                     if(!$supplier){
                         return false;
                     }
                    return [
                        'id'=>$supplier->id,
                        'realName'=>$supplier->getRealName(),
                        'company_name'=>$supplier->getCompanyName(),
                        'brand_name'=>$supplier->getBrandName(),
                        'mobile'=>$supplier->getMobile(),
                        'telephone'=>$supplier->getTelephone(),
                        'address'=>$supplier->getAddress(),
                        'areaCode'=>$supplier->getAreaCode(),
                    ];
               },
               'rule'=>function($rule){
                   if(!$rule)return false;
                    return [
                        'id'=>$rule->id,
                        'start_time'=>$rule->getStartTime(),
                        'end_time'=>$rule->getEndTime(),
                        'money_limit_type'=>$rule->money_limit_type,
                        'money_limit'=>$rule->money_limit,
                        'post_limit'=>$rule->post_limit,
                        'post_ready'=>$rule->post_ready,
                        'supply_limit'=>$rule->supply_limit,
                        'status'=>$rule->status,
                        'supply_list'=>$rule->getSuppliersList(),
                    ];
               },
           ],
       ]);
    }


    //创建优惠券信息
    public function createCoupon(){
        $supplyUser = new SupplyUser(['id' => $this->supply_user_id]);

        if(CouponHandler::create($this->name,$this->price,$this->total_quantity,strtotime($this->start_time),strtotime($this->end_time),$this->total_limit,$this->receive_limit,$supplyUser)){
            return true;
        }
        $this->addError('createCoupon',5227);
        return false;

    }



    //获取优惠券列表
    public function getCouponList(){
        $model=CouponHandler::search($this->page_size,$this->current_page,$this->status,$this->name);
        $data=array_map(function($item){
            $coupon=new Coupon(['id'=>$item['id']]);
            $supplier=$coupon->getSupplier();
            return [
                'id'=>$coupon->id,
                'name'=>$coupon->name,
                'price'=>$coupon->price,
                'consume_limit'=>$coupon->total_limit,
                'receive_limit'=>$coupon->receive_limit,
                'total_quantity'=>$coupon->total_quantity,
                'send_quantity'=>$coupon->send_quantity,
                'start_time'=>$coupon->getStartTime(),
                'end_time'=>$coupon->getEndTime(),
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
                'status'=>$coupon->status,
                'statusTxt'=>$coupon->getStatusTxt(),
            ];

        },$model->models);

        return [
            'count' => $model->count,
            'total_count' => $model->totalCount,
            'codes' => $data,
        ];

    }

}
