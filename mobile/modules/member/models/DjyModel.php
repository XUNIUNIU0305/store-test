<?php
namespace mobile\modules\member\models;

use Yii;
use common\models\Model;
use api\models\DjyModel as Djy;

class DjyModel extends Model{

    const SCE_GET_TOTAL = 'get_total';
    const SCE_GET_SKU = 'get_sku';
    const SCE_GET_STORE_LIST = 'get_store_list';
    const SCE_GET_ORDER_LIST = 'get_order_list';

    public $account;
    public $current_page;
    public $page_size;

    public function scenarios(){
        return [
            self::SCE_GET_TOTAL => [],
            self::SCE_GET_SKU => [],
            self::SCE_GET_STORE_LIST => [
                'current_page',
                'page_size',
            ],
            self::SCE_GET_ORDER_LIST => [
                'account',
                'current_page',
                'page_size',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['current_page'],
                'default',
                'value' => 1,
            ],
            [
                ['page_size'],
                'default',
                'value' => 10,
            ],
            [
                ['account'],
                'required',
                'message' => 9001,
            ],
        ];
    }

    public function getTotal(){
        $djyModel = new Djy([
            'quaternary_area_id' => $this->getQuaternaryAreaId(),
        ]);
        return $djyModel->getQuaternaryAreaFee();
    }

    public function getSku(){
        $djyModel = new Djy([
            'quaternary_area_id' => $this->getQuaternaryAreaId(),
        ]);
        return $djyModel->getQuaternaryAreaSku();
    }

    public function getStoreList(){
        $djyModel = new Djy([
            'quaternary_area_id' => $this->getQuaternaryAreaId(),
            'current_page' => $this->current_page,
            'page_size' => $this->page_size,
        ]);
        return $djyModel->getStoreFeeList();
    }

    public function getOrderList(){
        $djyModel = new Djy([
            'top_area_id' => null,
            'secondary_area_id' => null,
            'tertiary_area_id' => null,
            'quaternary_area_id' => null,
            'account' => $this->account,
            'current_page' => $this->current_page,
            'page_size' => $this->page_size,
        ]);
        return $djyModel->getOrderList();
    }

    protected function getQuaternaryAreaId(){
        return Yii::$app->user->identity->business_quaternary_area_id;
    }
}
