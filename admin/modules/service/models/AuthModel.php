<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/7/27
 * Time: 上午11:44
 */

namespace admin\modules\service\models;


use common\ActiveRecord\CustomUserAR;
use common\ActiveRecord\CustomUserAuthorizationAR;
use common\ActiveRecord\CustomUserAuthorizationDataAR;
use common\ActiveRecord\PartnerApplyAR;
use common\components\handler\Handler;
use common\components\handler\PromoterHandler;
use common\models\Model;
use common\models\parts\partner\Authorization;
use business\models\parts\Account;
use common\models\parts\custom\CustomUser;
use common\models\parts\partner\AuthorizeData;
use common\models\parts\partner\PartnerApply;
use Yii;

class AuthModel extends Model
{
    const SCE_LIST = 'get_list';
    const SCE_DETAIL = 'detail';
    const SCE_PASS = 'pass';
    const SCE_REJECT = 'reject';
    const SCE_CANCEL = 'cancel';
    const SCE_REFUND_NUMBER = 'set_refund_number';


    public $current_page;
    public $page_size;

    //id为pf_partner_apply 主键id; aid 为pf_custom_user_authorization主键id
    public $id,$aid, $comment;

    public $cancel;//是否注销 0未注销 1：已注销
    public $refund_number;
    public $is_operation;//注销列表  是否操作



    //搜索
    public $search_text; //手机和账号
    public $status;//已审核  未审核
    public $time;//asc desc

    public function scenarios()
    {
        return [
            self::SCE_LIST=>['current_page','page_size','search_text','status','time','cancel','is_operation'],
            self::SCE_DETAIL=>['id','aid'],
            self::SCE_PASS=>['aid'],
            self::SCE_REJECT=>['aid','comment'],
            self::SCE_CANCEL=>['id'],
            self::SCE_REFUND_NUMBER=>['id','refund_number']
        ];
    }

    public function rules()
    {
        return [
            [
                ['cancel'],
                'in',
                'range'=>[0,1],
                'message'=>9002,
            ],

            [
                ['is_operation'],
                'in',
                'range'=>[0,1],
                'message'=>9002,
            ],
            [
                ['refund_number'],
                'string',
                'length' => [
                    1,
                    50
                ],
                'message'=>9002,
            ],
            [
                ['current_page'],
                'default',
                'value'=>1,
            ],
            [
                ['page_size'],
                'default',
                'value'=>10,
            ],
            [
                ['current_page','page_size','refund_number','cancel','id'],
                'required',
                'message'=>9001,
            ],
            [
                ['comment'],
                'string',
                'length' => [
                    1,
                    100
                ],
                'tooShort' => 5293,
                'tooLong' => 5294,
                'message' => 5295,
            ],
            [
                ['status'],
                'common\validators\business\StatusValidator',
                'message'=>5296,
            ],
            [
                ['aid'],
                'each',
                'rule' => [
                    'exist',
                    'targetClass'=>CustomUserAuthorizationAR::className(),
                    'targetAttribute'=>['aid'=>'id'],
                    'message'=>5290,
                ],
                'on' => self::SCE_PASS,
            ],
            [
                ['aid'],
                'exist',
                'targetClass'=>CustomUserAuthorizationAR::className(),
                'targetAttribute'=>['aid'=>'id'],
                'message'=>5290,
                'except' => self::SCE_PASS,
            ],
            [
                ['id'],
                'exist',
                'targetClass'=>PartnerApplyAR::className(),
                'targetAttribute'=>['id'=>'id'],
                'message'=>5290,
            ],
        ];
    }

    /**
     *====================================================
     * 审核列表页
     * @return array
     * @author shuang.li
     *====================================================
     */
    public function getList(){
        try{
            $where = '';
            $sort = 'desc';
            if(!empty($this->search_text)){
                $where .= " and (r.account = {$this->search_text} or p.mobile = {$this->search_text} )";
            }
            if (!empty($this->time) && in_array($this->time,['asc','desc'])){
                $sort = $this->time;
            }
            switch ($this->is_operation) {
            case '0':
                $where .=" and  p.refund_number = '' ";
                break;
            case '1':
                $where .=" and  p.refund_number <> '' ";
                break;
            }
            $auth = PromoterHandler::getAuthorizationList($this->current_page,$this->page_size,$where,$sort,$this->status,$this->cancel);

            return [
                'auth'=>array_map(function($data){
                    return array_merge([
                        'id'=>$data['id'] ? : '',
                        'pid'=>$data['pid'] ? : '',
                        'status'=>$data['status']? :'',
                        'contact_name'=>$data['contact_name'] ? : '',
                        'contact_mobile'=>$data['contact_mobile'] ? : '',
                        'register_mobile'=>$data['mobile'] ? :'',//注册手机号
                        'account'=>$data['account'] ? : '',
                        'refund_number'=>$data['refund_number'] ? : '',
                    ],Handler::getMultiAttributes(new PartnerApply(['id'=>$data['pid']]),[
                        'pay'=>'pay',//支付信息
                        'promoter_id'=>  'partnerPromoter',
                        '_func'=>[
                            'partnerPromoter' => function ($promoter){
                                $promoterUserObj = $promoter->user;
                                if($promoterUserObj instanceof Account){
                                    $inviteUser = $promoterUserObj->name.'(运营商)';
                                }elseif ($promoterUserObj instanceof CustomUser){
                                    $inviteUser = $promoterUserObj->account.'('.
                                        ($promoterUserObj->province->name ?? '').
                                        ($promoterUserObj->city->name ?? '').
                                        ($promoterUserObj->district->name ?? '').')';
                                }else{
                                    throw new \Exception();
                                }
                                return [
                                    'id' => $promoter->id,//邀请码id'
                                    'invite_user'=>$inviteUser,//邀请人
                                    'invite_mobile'=>$promoterUserObj->mobile//邀请人
                                ];
                            }
                        ]
                    ]),$data['id']? Handler::getMultiAttributes(new Authorization(['id'=>$data['id']]),[
                        'submit_time'=>'authorizeApplyTime',//提交时间
                        '_func'=>[
                            'authorizeApplyTime'=>function($time){
                                return empty($time)?'':$time;
                            },

                        ],
                    ]):[]);
                },$auth->models),
                'count'=>$auth->count,
                'total_count'=>$auth->totalCount,
            ];
        }catch (\Exception $exception){
            echo $exception->getMessage();
            return [
                'auth'=>[],
                'count'=>'',
                'total_count'=>'',
            ];
        }
    }


    /**
     *====================================================
     * 审核详情
     * @return array
     * @author shuang.li
     *====================================================
     */
    public function detail(){
        try{
            $model = new PartnerApply(['id'=>$this->id]);

            $inviteInfo = [];
            if(($businessUser =$model->getPartnerPromoter()->getUser()) instanceof  Account){
                $parent1 = $businessUser->area->parent;
                $parent2 = $businessUser->area->parent->parent;
                $inviteInfo = [
                    'status'=>1,
                    'promoter_type'=> 1,
                    'business_account'=>$businessUser->account,
                    'business_name'=>$businessUser->name,
                    'business_leader'=>($leader = $businessUser->area->leader)?$leader->name: '',
                    'leader_mobile'=> $leader ?$leader ->mobile :'',
                    'city'=>$parent1->name,
                    'city_leader'=>($cityLeader = $parent1->leader) ? $cityLeader->name : '' ,
                    'city_leader_mobile'=>$cityLeader ? $cityLeader->mobile: '',
                    'province'=>$parent2->name,
                    'province_leader'=>($businessLeader = $parent2->leader) ? $businessLeader->name :'' ,
                    'province_leader_mobile'=>$businessLeader ? $businessLeader->mobile:'' ,
                ];
            }elseif (($customUser = $model->getPartnerPromoter()->getUser()) instanceof  CustomUser){
                $address = $customUser->getDefaultAddress();
                $inviteInfo = [
                    'promoter_type'=> 2,
                    'be_invited'=>'',
                    //默认收货地址
                    'default_address'=> $address ? [
                        'detailed_address'=>$address->detail,
                        'postal_code'=>$address->postalCode,
                        'consignee'=>$address->consignee,
                        'mobile'=>$address->mobile,
                    ] : [],
                    //系统预留信息
                    'have_info'=>[
                        'account'=>$customUser->account,
                        'nick_name'=>$customUser->nickName,
                        'district'=> (($province = $customUser->province) ? $province->name : '').'-'
                            .(($city = $customUser->city)?$city->name : '').'-'
                            .(($district = $customUser->district) ? $district->name: ''),
                        'area'=>implode('-',array_reverse([
                            ($five = $customUser->area) ? $five->name: '',
                            ($four = $five->parent)?$four->name : '',
                            ($three = $four->parent) ? $three->name: '',
                            ($two = $three->parent) ? $two->name: '',
                            ($one = $two->parent) ? $one->name: '',
                        ])) ,
                        'mobile'=>$customUser->mobile?:'',
                        'mobile_bak'=>$customUser->mobileBak?:'',
                        'email'=>$customUser->email,
                    ],
                    //微信信息
                    'wechat'=>($wechat = $customUser->wechatUser) ? [
                        'nick_name'=>$wechat->nickName,
                        'sex'=>$wechat->sex,
                        'city'=>$wechat->city,
                        'country'=>$wechat->country,
                        'header_img'=>$wechat->headImageUrl,
                    ] : [],

                ];
            }
            $applyInfo = [];
            if (!empty($this->aid)){
                $model = new Authorization(['id'=>$this->aid]);
                $applyInfo = array_merge(['be_invited'=>$model->getBeInvited()? :'','status'=>$model->getStatus() ? :''],($authorizeData = $model->getAuthorizeData()) ?  Handler::getMultiAttributes($authorizeData,[
                    //申请人信息
                    'authorization',
                    'store_name' => 'storeName',
                    'corp_name' => 'corpName',
                    'email',
                    'address',
                    'manager_name' => 'managerName',
                    'contact_name' => 'contactName',
                    'contact_mobile' => 'contactMobile',
                    'district' => 'district',
                    'card_front' => 'managerIdcardFront',
                    'card_back' => 'managerIdcardBack',
                    'store_front' => 'storeFront',
                    'store_inside' => 'storeInside',
                    'business_licence' => 'businessLicence',
                    'comment' => 'AuthorizeComment',
                    '_func' => [
                        'contactMobile' => function($mobile){
                            if($mobile){
                                return $mobile;
                            }else{
                                return '';
                            }
                        },
                        'district' => function ($district){
                            return [
                                'province' => [
                                    'id' => $district->province->provinceId ? : '',
                                    'name' => $district->province->name ? : '',
                                ],
                                'city' => [
                                    'id' => $district->city->cityId ? : '',
                                    'name' => $district->city->name ? : '',
                                ],
                                'district' => [
                                    'id' => $district->districtId ? : '',
                                    'name' => $district->name ? : '',
                                ],
                            ];
                        },
                        'managerIdcardFront'=>function($card){
                            if($card){
                                return [
                                    'name'=>$card->name,
                                    'path'=>$card->path
                                ];
                            }else{
                                return [
                                    'name' => '',
                                    'path' => '',
                                ];
                            }
                        },
                        'managerIdcardBack'=>function($card){
                            if($card){
                                return [
                                    'name'=>$card->name,
                                    'path'=>$card->path
                                ];
                            }else{
                                return [
                                    'name' => '',
                                    'path' => '',
                                ];
                            }
                        },
                        'storeFront'=>function($store){
                            if($store){
                                return [
                                    'name'=>$store->name,
                                    'path'=>$store->path
                                ];
                            }else{
                                return [
                                    'name' => '',
                                    'path' => '',
                                ];
                            }
                        },
                        'storeInside'=>function($store){
                            if($store){
                                return [
                                    'name'=>$store->name,
                                    'path'=>$store->path
                                ];
                            }else{
                                return [
                                    'name' => '',
                                    'path' => '',
                                ];
                            }
                        },
                        'businessLicence'=>function($licence){
                            if($licence){
                                return [
                                    'name'=>$licence->name,
                                    'path'=>$licence->path
                                ];
                            }else{
                                return [
                                    'name' => '',
                                    'path' => '',
                                ];
                            }
                        },
                    ]
                ]):[]);

            }
            return  [
                'apply_info'=>$applyInfo,
                'invite_info'=>$inviteInfo
            ];
        }catch (\Exception $exception){ 
            return [];
        }
    }
    /**
     *====================================================
     * 通过
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function pass(){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            foreach($this->aid as $id){
                if(!$id)continue;
                $model = new Authorization([
                    'id' => $id,
                ]);
                if($model->getStatus() == Authorization::STATUS_PAID || $model->getStatus() == Authorization::STATUS_AUTHORIZE_FAIL){
                    $model->newAuthorizeData()
                        ->addStore('未知店名')
                        ->addCorpName()
                        ->addEmail($model->customUser->account . '@unknown')
                        ->addAddress(new \common\models\parts\district\District([
                            'provinceId' => 9,
                            'cityId' => 74,
                            'districtId' => 721,
                        ]), '未知地址')
                        ->addManager('未知负责人')
                        ->addContact()
                        ->addBusinessLicence()
                        ->build();
                }
                if($model->setStatus(Authorization::STATUS_AUTHORIZE_SUCCESS) == false)throw new \Exception;
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            $this->addError('pass', 5291);
            return false;
        }
    }

    /**
     *====================================================
     * 驳回
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function reject(){
        try {
            $model = new Authorization(['id'=>$this->aid]);
            if (($model->setStatus(Authorization::STATUS_AUTHORIZE_FAIL)) !== false && ($model->authorizeData->setAuthorizeComment($this->comment)) !==false) return true;
        }catch (\Exception $exception){
            $this->addError('pass',5292);
            return false;
        }

    }


    /**
     *====================================================
     * 注销
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function cancel(){
        //开启事物
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = new PartnerApply(['id'=>$this->id]);
            $registerCodeObj = $model->getRegisterCode();

            $account = $registerCodeObj->account;
            if(Yii::$app->RQ->AR(new CustomUserAR())->exists([
                'where'=>['account'=>$account],
                'limit'=>1
            ])){
                //已注册  当前账号设置为不可用
                $res = (new \admin\modules\site\models\parts\UserHandler)->cancel(new CustomUser(['account' => $account]));
            }else{
                //未注册  注册码不可用
                $res = $registerCodeObj->setUnAvailable();
            }
            if ($res!==false && $model->setCancel()){
                $transaction->commit();
                return true;
            }
        }catch (\Exception $exception){
            $this->addError('cancel',5310);
            $transaction->rollBack();
            return false;
        }
    }

    /**
     *====================================================
     * 设置退款单号
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function setRefundNumber(){
        try {
            $model = new PartnerApply(['id'=>$this->id]);
            if (($model->setRefundNumber($this->refund_number)) !== false ) return true;
        }catch (\Exception $exception){
            $this->addError('setRefundNumber',5311);
            return false;
        }
    }




}
