<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/10
 * Time: 17:39
 */

namespace api\models;


use common\components\handler\ExpressCorporationHandler;
use common\models\Model;
use common\models\parts\ExpressCorporation;

class ExpressModel extends Model
{


    const SCE_GET_EXPRESS_COMPANY_LIST = "get_express_company";//获取物流公司信息


    public function scenarios()
    {
        return [
            self::SCE_GET_EXPRESS_COMPANY_LIST => [],
        ];
    }

    public function rules()
    {
        return [];
    }

    public function getExpressCompany()
    {

        return ExpressCorporation::getExpressItems();

    }

}