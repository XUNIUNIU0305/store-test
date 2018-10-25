<?php
/**
 * Created by PhpStorm.
 * User: forrestgao
 * Date: 18-4-11
 * Time: 下午12:53
 */

namespace api\models;

use Yii;
use common\ActiveRecord\BusinessUserAR;
use common\models\Model;

class BusinessModel extends Model
{
    public $account;
    public $mobile;
    public $passwd;

    const SCE_VALIDATE_ACCOUNT = 'validate_account';

    public function scenarios()
    {
        return [
            self::SCE_VALIDATE_ACCOUNT => [
                'account',
                'mobile',
                'passwd',
            ],
        ];
    }

    public function rules()
    {
        return [];
    }

    public function validateAccount()
    {
        if ($this->account) {
            $user = BusinessUserAR::findOne([
                'account' => $this->account,
            ]);
        } elseif (strlen($this->mobile) == 11) {
            $user = BusinessUserAR::findOne([
                'mobile' => $this->mobile,
            ]);
        } else {
            $this->addError('validateAccount', 9001);
            return false;
        }

        if (!$user) {
            $this->addError('validateAccount', 7120);
            return false;
        }
        if ($user->status == 1) {
            $this->addError('validateAccount', 7121);
            return false;
        } elseif ($user->status == 2) {
            $this->addError('validateAccount', 7123);
            return false;
        }
        if (empty($this->passwd)) {
            $this->addError('validateAccount', 9001);
            return false;
        }

        if ($user && Yii::$app->security->validatePassword($this->passwd, $user->passwd)) {
            return [
                'account' => $user->account,
                'mobile' => $user->mobile ?: '',
            ];
        } else {
            $this->addError('validateAccount', 7122);
            return false;
        }
    }
}