<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/5/15
 * Time: 下午5:27
 */

namespace admin\modules\site\models;

use admin\components\handler\AttributeHandler;
use admin\modules\site\models\parts\UserHandler;
use common\ActiveRecord\CustomUserAR;
use common\components\handler\Handler;
use common\models\Model;
use common\models\parts\custom\CustomUser;
use common\models\parts\Order;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use Yii;

class UserModel extends Model
{
    const SCE_USER_INFO = 'user_info';
    const SCE_ORDER_INFO = 'order_info';
    const SCE_MODIFY_PASSWORD = 'modify_password';
    const SCE_CANCEL_ACCOUNT = 'cancel_account';
    const SCE_RESET_PASSWORD = 'reset_password';
    const SCE_UNBIND_MOBILE = 'unbind_mobile';
    const SCE_UPGRADE_USER = 'upgrade_user';
    const SCE_SAVE = 'save';

    public $customer_user_id;
    public $search_name;
    public $current_page;
    public $page_size;
    private $password = '111111111';
    public $action;

    public function scenarios()
    {
        return [
            self::SCE_USER_INFO => ['search_name'],
            self::SCE_ORDER_INFO => ['customer_user_id', 'current_page', 'page_size'],
            self::SCE_CANCEL_ACCOUNT => ['customer_user_id'],
            self::SCE_RESET_PASSWORD => ['customer_user_id'],
            self::SCE_UNBIND_MOBILE => ['customer_user_id'],
            self::SCE_UPGRADE_USER => ['customer_user_id', 'action'],
        ];
    }

    public function rules()
    {
        return [
            [['customer_user_id', 'search_name', 'current_page', 'page_size', 'action'], 'required', 'message' => 9001],
            [
                ['customer_user_id'],
                'common\validators\custom\CustomUserInfoValidator',
                'action' => ($this->scenario == self::SCE_UPGRADE_USER) ? $this->action : null,
                'mobile' => ($this->scenario == self::SCE_UNBIND_MOBILE) ? true : null,
                'message' => 5254,
                'upmessage' => 5284,
                'dropmessage' => 5285,
                'mobilemessage' => 5274,
                'actionmessage' => 5441,
            ],
            [['current_page'], 'default', 'value' => 1],
            [['page_size'], 'default', 'value' => 10],
        ];
    }

    /**
     *====================================================
     * 获取用户信息
     * @return array
     * @author shuang.li
     *====================================================
     */
    public function userInfo()
    {
        $searchLength = strlen($this->search_name);
        try {
            if ($searchLength == 11) {
                $customUser = new CustomUser(['mobile' => $this->search_name]);
            } elseif ($searchLength == 9) {
                $customUser = new CustomUser(['account' => $this->search_name]);
            } else {
                throw new Exception('Account does not exist.');
            }
        } catch (\Exception $e) {
        }

        if (!isset($customUser)) {
            try {
                if ($account = CustomUserAR::find()->select(['account'])->where(['mobile_bak' => $this->search_name])->scalar()) {
                    $customUser = new CustomUser(['account' => $account]);
                    if ($customUser->mobile) {
                        $this->addError('userInfo', 5254);
                        return false;
                    }
                }
            } catch (\Exception $e) {
                $this->addError('userInfo', 5254);
                return false;
            }
        }

        if (!isset($customUser)) {
            $this->addError('userInfo', 5254);
            return false;
        }

        return Handler::getMultiAttributes($customUser, [
            'id',
            'account',
            'balance' => 'customUserBalance',
            'fiveArea' => 'area',
            'province',
            'level',
            'city',
            'district',
            'status',
            'headerImg',
            'shopName',
            'nickName',
            'mobile',
            'level',
            'wechatUserName',
            '_func' => [
                'area' => function ($area) {
                    return array_map(function ($area) {
                        return Handler::getMultiAttributes($area, [
                            'id',
                            'name',
                        ]);
                    }, $area->fullArea);
                },
                'province' => function ($province) {
                    if (!$province) return false;
                    return ['id' => $province->provinceId, 'name' => $province->name];
                },
                'city' => function ($city) {
                    if (!$city) return false;
                    return ['id' => $city->cityId, 'name' => $city->name];
                },
                'district' => function ($district) {
                    if (!$district) return false;
                    return ['id' => $district->districtId, 'name' => $district->name];
                },
            ],
        ]);

    }

    /**
     *====================================================
     * 获取用户订单信息
     * @return array
     * @author shuang.li
     *====================================================
     */
    public function orderInfo()
    {
        $ordersProvide = (new CustomUser(['id' => $this->customer_user_id]))->getOrders($this->current_page, $this->page_size);
        $orderIds = ArrayHelper::getColumn($ordersProvide->models, 'id');
        $emptyFunc = function ($data) {
            return empty($data) ? '' : $data;
        };
        $orders = array_map(function ($id) use ($emptyFunc) {
            return Handler::getMultiAttributes(new Order(['id' => $id]), [
                'order_no' => 'orderNo',//订单号
                'status',//订单状态
                'express_number' => 'expressNo',//运单号
                'express_corporation' => 'expressCorpName',//快递公司
                'pay_time' => 'payTime',//付款时间
                'address',//收货地址
                'items',
                '_func' => [
                    'expressNo' => $emptyFunc,
                    'expressCorpName' => $emptyFunc,
                    'payTime' => $emptyFunc,
                    'items' => function ($items) {
                        return array_map(function ($item) {
                            return Handler::getMultiAttributes($item, [
                                'title',
                                'attributes' => 'SKUAttributes',
                                'price',
                                'count',
                                'total_fee' => 'totalFee',
                                'image',
                                '_func' => [
                                    'image' => function ($image) {
                                        return $image->path;
                                    },

                                ],
                            ]);
                        }, $items);
                    },
                ],
            ]);
        }, $orderIds);

        return ['orders' => $orders, 'count' => $ordersProvide->count, 'totalCount' => $ordersProvide->totalCount];

    }

    /**
     * 注销账号
     * @return bool
     * @author forrestgao
     */
    public function cancelAccount()
    {
        $customUser = new CustomUser(['id' => $this->customer_user_id]);
        if ($customUser->status != CustomUser::STATUS_NORMAL) {
            $this->addError('create', 5445);
            return false;
        }

        if ((new UserHandler())->cancel($customUser)) {
            return true;
        } else {
            $this->addError('cancelAccount', 5600);
            return false;
        }
    }

    /**
     * 重置密码为初始秘密
     */
    public function resetPassword()
    {
        $customer = new CustomUser(['id' => $this->customer_user_id]);
        try {
            $customer->setPassword($this->password);
            return true;
        } catch (\Exception $e) {
            $this->addError('resetPassword', 5257);
            return false;
        }
    }

    /**
     * 解绑手机号
     */
    public function unbindMobile()
    {
        $customUser = new CustomUser(['id' => $this->customer_user_id]);
        if ((new UserHandler())->unbind($customUser)) {
            return true;
        } else {
            $this->addError('unbindMobile', 5322);
            return false;
        }
    }

    /**
     * 升降级用户
     */
    public function upgradeUser()
    {
        $customUser = CustomUserAR::findOne(['id' => $this->customer_user_id]);
        if ((new UserHandler())->upgrade($customUser, $this->action)) {
            return true;
        } else {
            $this->addError('upgradeUser', 5283);
            return false;
        }
    }

}
