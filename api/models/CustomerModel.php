<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 15:34
 */

namespace api\models;


use common\ActiveRecord\CustomUserAR;
use common\models\Model;
use common\models\parts\custom\CustomUser;

class CustomerModel extends Model
{

    const SCE_GET_TECHNICIAN_LIST = "get_technician_list";//获取门店技术列表

    public $customer_code;//客户ID

    public function scenarios()
    {
        return [
            self::SCE_GET_TECHNICIAN_LIST => ['customer_code'],
        ];
    }

    //获取规则
    public function rules()
    {
        return [
            [
                ['customer_code'],
                'required',
                'message' => 9001,
            ],
            [
                ['customer_code'],
                'string',
                'length' => [9, 9],
                'tooLong' => 7088,
                'tooShort' => 7088,
                'message' => 7088,
            ],
            [
                ['customer_code'],
                'exist',
                'targetClass' => CustomUserAR::className(),
                'targetAttribute' => ['customer_code' => 'account'],
                'message' => 7051,
            ],
        ];
    }


    //获取客户门店技师信息
    public function getTechnicianList()
    {
        if($list=(new CustomUser(['account' => $this->customer_code]))->getTechnician()){
            return $list;
        }
        $this->addError('getTechnicianList',7095);
        return false;
    }


}