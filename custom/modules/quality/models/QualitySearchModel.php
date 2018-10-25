<?php
/**
 * Created by PhpStorm.
 * User: forrest
 * Date: 14/05/18
 * Time: 15:54
 */

namespace custom\modules\quality\models;

use common\ActiveRecord\QualityOrderAR;
use common\ActiveRecord\QualityOrderItemAR;
use common\components\handler\Handler;
use common\components\handler\quality\QualityOrderHandler;
use common\models\Model;
use common\models\parts\quality\QualityOrder;
use common\models\parts\quality\QualityOrderItem;
use custom\models\LoginModel;
use custom\models\parts\Captcha;
use custom\models\parts\UserIdentity;
use custom\models\SmsModel;
use Yii;

class QualitySearchModel extends Model
{
    const SCE_SEND_MOBILE_CAPTCHA = 'send_mobile_captcha';
    const SCE_AUTH_BY_OWNER = 'auth_by_owner';
    const SCE_LIST_BY_OWNER = 'list_by_owner';
    const SCE_DETAIL_BY_OWNER = 'detail_by_owner';
    const SCE_AUTH_BY_CUSTOM = 'auth_by_custom';
    const SCE_SEARCH_BY_ORDERCODE = 'search_by_ordercode';
    const SCE_LIST_BY_ITEMCODE = 'list_by_itemcode';
    const SCE_DETAIL_BY_ITEMCODE = 'detail_by_itemcode';

    // 车牌号或车架号，车主手机，质保单号
    public $number_or_frame_of_car, $owner_mobile, $order_code;
    // 门店用户名或手机号，门店用户密码
    public $account_or_mobile_of_custom, $password;
    // 管芯号
    public $item_code;

    // 验证码
    public $captcha;
    // 手机验证码
    public $mobile_captcha;

    public function scenarios()
    {
        return [
            self::SCE_SEND_MOBILE_CAPTCHA => ['owner_mobile'],
            self::SCE_AUTH_BY_OWNER => ['number_or_frame_of_car', 'owner_mobile', 'mobile_captcha'],
            self::SCE_LIST_BY_OWNER => [],
            self::SCE_DETAIL_BY_OWNER => ['order_code'],
            self::SCE_AUTH_BY_CUSTOM => ['account_or_mobile_of_custom', 'password', 'captcha'],
            self::SCE_SEARCH_BY_ORDERCODE => ['order_code'],
            self::SCE_LIST_BY_ITEMCODE => ['item_code'],
            self::SCE_DETAIL_BY_ITEMCODE => ['order_code'],
        ];
    }

    public function rules()
    {
        return [
            [
                [
                    'number_or_frame_of_car', 'owner_mobile', 'mobile_captcha', 'order_code',
                    'account_or_mobile_of_custom', 'password', 'captcha', 'item_code',
                ],
                'required', 'message' => 9001,
            ],
            ['number_or_frame_of_car', 'match', 'pattern' => '/[u4e00-u9fa5a-zA-Z0-9]{6,17}$/', 'message' => 3403],
            ['owner_mobile', 'match', 'pattern' => '/^1[0-9]{10}$/', 'message' => 3406],
            ['order_code', 'match', 'pattern' => '/^[\w]+$/', 'message' => 3405],
            ['account_or_mobile_of_custom', 'match', 'pattern' => '/^\d{9,11}$/', 'message' => 3404],
            ['password', 'match', 'pattern' => '/.{1,60}/', 'message' => 3186],
            ['account_or_mobile_of_custom', 'match', 'pattern' => '/.{1,40}/', 'message' => 3382],
            [
                ['captcha'],
                'captcha',
                'captchaAction' => 'index/captcha',
                'message' => 3011,
            ],
            [
                ['mobile_captcha'],
                'common\validators\SmsValidator',
                'mobile' => $this->owner_mobile,
                'message' => 3364,
            ],
        ];
    }

    // 发送手机验证码
    public function sendMobileCaptcha()
    {
        if (!QualityOrderAR::find()->where(['owner_mobile' => $this->owner_mobile])->exists()) {
            $this->addError('sendMobileCaptcha',3365);
            return false;
        } else {
            $model = new SmsModel(['mobile' => $this->owner_mobile, 'type' => 6]);
            if ($model && $model->sendSms()) {
                return true;
            } else {
                $this->addError('sendMobileCaptcha',3407);
                return false;
            }
        }
    }

    // 车主查询页
    public function authByOwner()
    {
        if (mb_strlen($this->number_or_frame_of_car) == QualityOrderItem::CAR_FRAME_LENGTH) {
            $field = 'car_frame';
        } else {
            $field = 'car_number';
        }

        $ids = QualityOrderAR::find()->select(['id'])->where([
            $field => $this->number_or_frame_of_car,
            'owner_mobile' => $this->owner_mobile
        ])->column();

        if (count($ids) === 1) {
            Yii::$app->session->set('ownerIsAuthorization', 1);
            Yii::$app->session->set('orderIds', $ids);
            return ['order_code' => QualityOrderAR::find()->select(['code'])->where(['id' => current($ids)])->scalar()];
        } elseif (count($ids) > 1) {
            Yii::$app->session->set('ownerIsAuthorization', 1);
            Yii::$app->session->set('orderIds', $ids);
            return ['order_code' => null];
        } else {
            $this->addError('authByOwner',3365);
            return false;
        }
    }

    // 车主质保单详情页
    public function listByOwner()
    {
        if (Yii::$app->session->get('ownerIsAuthorization') === 1) {
            $orderIds = Yii::$app->session->get('orderIds');
            $datas = [];
            foreach ($orderIds as $id) {
                if ($id && Yii::$app->RQ->AR(new QualityOrderItemAR())->exists([
                        'where' => ['quality_order_id' => $id]
                    ])) {
                    $datas[] = Handler::getMultiAttributes(new QualityOrder(['id' => $id]), [
                        'code',
                        'construct_date' => 'constructTime',
                        'membraneBrand' => 'membraneBrand'
                    ]);
                }
            }
            if (count($datas) >= 1) {
                return $datas;
            } else {
                $this->addError('listByOwner',3361);
                return false;
            }
        } else {
            $this->addError('listByOwner', 3362);
            return false;
        }
    }

    // 车主查询详情页
    public function detailByOwner()
    {
        if (Yii::$app->session->get('ownerIsAuthorization') === 1) {
            $this->order_code = strtoupper($this->order_code);
            if ($detail = QualityOrderHandler::getOwnerList($this->order_code)) {
                return $detail;
            } else {
                $this->addError('detailByOwner', 3365);
                return false;
            }
        } else {
            $this->addError('detailByOwner', 3362);
            return false;
        }
    }

    // 服务商查询页
    public function authByCustom()
    {
        if (Yii::$app->user->isGuest) {
            // 登录操作
            $model = new LoginModel(['account' => $this->account_or_mobile_of_custom, 'passwd' => $this->password]);
            if ($model && $model->login()) {
                return true;
            } else {
                $this->addError('authByCustom', 3381);
                return false;
            }
        } else {
            return true;
        }
    }

    // 按质保单号查询
    public function searchByOrdercode()
    {
        if (!Yii::$app->user->isGuest) {
            $this->order_code = strtoupper($this->order_code);
            if ($list = QualityOrderHandler::getCustomList($this->order_code)) {
                return $list;
            } else {
                $this->addError('searchByOrdercode', 3365);
                return false;
            }
        } else {
            $this->addError('searchByOrdercode', 3363);
            return false;
        }
    }

    // 管芯号查询列表页
    public function listByItemcode()
    {
        if (!Yii::$app->user->isGuest) {
            $this->item_code = strtoupper($this->item_code);
            if ($list = QualityOrderHandler::getCustomList($this->item_code, true)) {
                return $list;
            } else {
                $this->addError('listByItemcode', 3370);
                return false;
            }
        } else {
            $this->addError('listByItemcode', 3363);
            return false;
        }
    }

    // 管芯号查询详情页
    public function detailByItemcode()
    {
        if (!Yii::$app->user->isGuest) {
            $this->order_code = strtoupper($this->order_code);
            if ($detail = QualityOrderHandler::getOwnerList($this->order_code)) {
                return $detail;
            } else {
                $this->addError('detailByItemcode', 3365);
                return false;
            }
        } else {
            $this->addError('detailByOwner', 3363);
            return false;
        }
    }
}