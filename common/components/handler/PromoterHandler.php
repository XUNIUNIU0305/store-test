<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/7/19
 * Time: 上午9:54
 */

namespace common\components\handler;

use common\ActiveRecord\CustomUserAR;
use common\ActiveRecord\CustomUserAuthorizationAR;
use common\ActiveRecord\CustomUserRegistercodeAR;
use common\ActiveRecord\PartnerApplyAR;
use common\ActiveRecord\PartnerPromoterAR;
use common\models\parts\partner\Authorization;
use custom\models\parts\RegisterCode;
use yii\data\ActiveDataProvider;
use Yii;
class PromoterHandler extends Handler
{
    public static function create($data){
        return Yii::$app->RQ->AR(new PartnerPromoterAR)->insert($data);
    }

    public static function providesQrcode($currentPage,$pageSize,$where){
        return  new ActiveDataProvider([
            'query' => PartnerPromoterAR::find()->select(['id', 'title','is_available'])->where($where)->asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => [
                    'is_available' => SORT_DESC,
                    'id' => SORT_DESC,
                ],
            ],
        ]);
    }
    public static function inviteLogList($currentPage,$pageSize,$where,$status = null){
         $authWhere = [
            'select'=>'partner_apply_id',
            'where'=>['>','id',0],
        ];
        if (strpos(',',$status)){
            $authWhere = array_merge($authWhere,['andWhere'=>['in','status',explode(',',$status)]]) ;
            $payAuthId= Yii::$app->RQ->AR(new CustomUserAuthorizationAR())->column($authWhere);
            $where .= ' and p.id in ('.implode(',',$payAuthId).')';
        }else{
            switch ($status) {
            case Authorization::STATUS_PAID :
                $authWhere = array_merge($authWhere,['andWhere'=>['in','status',[
                    Authorization::STATUS_AUTHORIZE_APPLY,
                    Authorization::STATUS_AUTHORIZE_FAIL,
                    Authorization::STATUS_AUTHORIZE_SUCCESS,
                    Authorization::STATUS_ACCOUNT_VALID
                ]]]);
                $payAuthId= Yii::$app->RQ->AR(new CustomUserAuthorizationAR())->column($authWhere);

                if (!empty($payAuthId)) {
                    $where .= ' and p.id not in ('.implode(',',$payAuthId).')';
                }
                break;
            case Authorization::STATUS_AUTHORIZE_APPLY :
            case Authorization::STATUS_AUTHORIZE_FAIL:
            case Authorization::STATUS_AUTHORIZE_SUCCESS:
            case Authorization::STATUS_ACCOUNT_VALID:
                $authWhere = array_merge($authWhere,['andWhere'=>['status'=>$status]]) ;
                $payAuthId= Yii::$app->RQ->AR(new CustomUserAuthorizationAR())->column($authWhere);
                if (!empty($payAuthId)) {
                    $where .= ' and p.id in ('.implode(',',$payAuthId).')';
                }else{
                    $where .= ' and p.id = 0';
                }
                break;
            }
        }
        return  new ActiveDataProvider([
            'query' =>PartnerApplyAR ::find()
                ->from(PartnerApplyAR::tableName().'p')
                ->select([
                    'p.id',
                    'p.partner_promoter_id',
                    'ifnull(p.mobile,"") as mobile',
                    'ifnull(a.status,1) as status',
                    'if(a.status =5,a.award_rmb,0) as award_rmb',
                    'p.pay_datetime',
                    'ifnull(a.authorized_datetime,"") as authorized_datetime',
                    'ifnull(a.account_valid_datetime,"") as account_valid_datetime',
                    'if(r.account is null, "", if(p.passwd = "", r.account, "*********")) as account'
                ])
                ->leftJoin('pf_custom_user_authorization AS a','a.partner_apply_id = p.id ')
                ->leftJoin('pf_custom_user_registercode AS r','p.custom_user_registercode_id = r.id')
                ->where($where)->asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);
    }


    public static function statusCount(){
        return  Yii::$app->RQ->AR(new CustomUserAuthorizationAR())->all([
            'select'=>['status','count(*) as count'],
            'where' => [
                'promoter_user_id' => Yii::$app->user->id,
                'promoter_type' => 1,
                'status' => [
                    Authorization::STATUS_AUTHORIZE_APPLY,
                    Authorization::STATUS_ACCOUNT_VALID,
                ],
            ],
            'groupBy' => 'status',
        ]);
    }


    public static function waitCount($promoterId){
        $authId = Yii::$app->RQ->AR(new CustomUserAuthorizationAR())->column([
            'select' => 'partner_apply_id',
            'where' => [
                'status' => [
                    Authorization::STATUS_AUTHORIZE_APPLY,
                    Authorization::STATUS_ACCOUNT_VALID,
                    Authorization::STATUS_AUTHORIZE_FAIL,
                    Authorization::STATUS_AUTHORIZE_SUCCESS
                ]
            ],
        ]);

        return  PartnerApplyAR::find()->where([
            'partner_promoter_id'=>$promoterId,
        ])->andWhere(['not in','id',$authId])->andWhere(['>','pay_unixtime',0])->count();

     }


    public static function getAmount(){
        return Yii::$app->RQ->AR(new CustomUserAuthorizationAR())->scalar([
            'select'=>['sum(award_rmb) as amount'],
            'where'=>[
                'promoter_user_id'=>Yii::$app->user->id,
                'promoter_type'=>1,
                'status'=>Authorization::STATUS_ACCOUNT_VALID,
            ],
        ]);
    }

    public static function getStreamCount($promoterId){
        return Yii::$app->RQ->AR(new PartnerApplyAR())->one([
            'select'=>['count(id) as count','sum(award_rmb) as rmb'],
            'where'=>[
                'partner_promoter_id'=>$promoterId,
             ],
            'andWhere'=>['>','pay_unixtime',0]
        ]);

    }

    public static function getUserInfo(){
        return Yii::$app->RQ->AR(new CustomUserAR())->one([
            'select'=>['nick_name','account'],
            'where'=>[
                'id'=>Yii::$app->user->id,
            ]
        ]);
    }

    /**
     *====================================================
     * 获取待审核，已审核信息列表
     * @param $currentPage
     * @param $pageSize
     * @param $sort
     * @param $status
     * @param $where
     * @param $cancel
     * @return ActiveDataProvider
     * @author shuang.li
     *====================================================
     */
    public static function getAuthorizationList($currentPage,$pageSize,$where,$sort,$status,$cancel){
        switch ($cancel) {
        case '0':
            if (strpos($status,',')){
                $statusArr = explode(',',$status);
                if(!array_diff([Authorization::STATUS_PAID,Authorization::STATUS_AUTHORIZE_FAIL],$statusArr)){
                    $authWhere = [
                        'select'=>'partner_apply_id',
                        'where' => [
                            'status' => [
                                Authorization::STATUS_AUTHORIZE_APPLY,
                                Authorization::STATUS_AUTHORIZE_SUCCESS,
                                Authorization::STATUS_ACCOUNT_VALID
                            ]
                        ]
                    ];
                    $payAuthId= Yii::$app->RQ->AR(new CustomUserAuthorizationAR())->column($authWhere);
                    $where = ' p.id not in ('.implode(',',$payAuthId).') and p.pay_unixtime > 0 and p.is_cancel = 0 ' .$where;
                }elseif(!array_diff([ Authorization::STATUS_AUTHORIZE_SUCCESS,Authorization::STATUS_ACCOUNT_VALID],$statusArr)){
                    $authWhere = [
                        'select'=>'partner_apply_id',
                        'where'=>[
                            'status'=>[
                                Authorization::STATUS_AUTHORIZE_SUCCESS,
                                Authorization::STATUS_ACCOUNT_VALID
                            ]
                        ],

                    ];
                    $payAuthId= Yii::$app->RQ->AR(new CustomUserAuthorizationAR())->column($authWhere);
                    $where = ' p.id in ('.implode(',',$payAuthId).') and p.pay_unixtime > 0 and p.is_cancel = 0 '.$where;
                }
            }
            if ($status == Authorization::STATUS_AUTHORIZE_APPLY) {
                $authWhere = [
                    'select'=>'partner_apply_id',
                    'where'=>[
                        'status'=> Authorization::STATUS_AUTHORIZE_APPLY
                    ]
                ];
                $payAuthId= Yii::$app->RQ->AR(new CustomUserAuthorizationAR())->column($authWhere);
                $where = ' p.id in ('.implode(',',$payAuthId).')  and p.pay_unixtime > 0 and p.is_cancel = 0 '.$where;
            }
            break;
        case '1':
            $where = '  p.pay_unixtime > 0 and p.is_cancel = 1 '.$where;
            break;
        }

        return  new ActiveDataProvider([
            'query' =>PartnerApplyAR ::find()
                ->from(PartnerApplyAR::tableName().'p')
                ->select([
                    'a.id',
                    'p.id as pid',
                    'p.refund_number',
                    'a.custom_user_id',
                    'r.account',
                    'a.partner_apply_id',
                    'p.mobile',
                    'a.partner_promoter_id',
                    'a.promoter_type',
                    'a.promoter_user_id',
                    'a.award_rmb',
                    'a.custom_user_authorization_data_id',
                    'a.status',
                    'p.pay_datetime',
                    'a.authorize_apply_datetime',
                    'a.authorized_datetime',
                    'a.account_valid_datetime',
                    'd.contact_name',
                    'd.contact_mobile',
                ])
                ->leftJoin('pf_custom_user_authorization AS a','a.partner_apply_id = p.id ')
                ->leftJoin('pf_custom_user_authorization_data AS d','a.custom_user_authorization_data_id = d.id ')
                ->leftJoin('pf_custom_user_registercode AS r','p.custom_user_registercode_id = r.id')
                ->where($where)->asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => [
                    'pay_datetime' => ($sort == 'desc') ? SORT_DESC : SORT_ASC,
                ],
            ],
        ]);

    }


}
