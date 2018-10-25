<?php
namespace admin\controllers;

use Yii;
use common\controllers\Controller;
use common\ActiveRecord\OrderAR;
use common\ActiveRecord\OrderItemAR;
use common\models\parts\Order;
use common\ActiveRecord\TempYougaZodiacNumberAR;
use common\ActiveRecord\CustomUserAddressAR;
use common\ActiveRecord\CustomUserAR;
use common\models\parts\Address;
use common\models\parts\business_area\TopArea;
use common\models\parts\district\Province;
use common\components\handler\ExcelHandler;
use admin\models\GenerateExcelModel;
use common\ActiveRecord\CustomUserStatementAR;
use common\ActiveRecord\CustomUserReceiveLogAR;
use common\ActiveRecord\CustomUserRegistercodeAR;

class GenerateExcelController extends Controller{

    protected $access = [
        'order' => ['@', 'get'],
        'zodiac' => ['@', 'get'],
        'user-default-address' => ['@', 'get'],
        'cancel-orders' => ['@', 'get'],
        'orders' => ['@', 'get'],
        't' => ['@', 'get'],
        'refund-order' => ['@', 'get'],
        'jiangsu' => [null, 'get'],
        'tree' => ['@', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'orders' => GenerateExcelModel::SCE_GET_ORDERS,
        'refund-order' => GenerateExcelModel::SCE_REFUND_ORDER,
        'jiangsu' => GenerateExcelModel::SCE_JIANGSU,
        '_model' => '\admin\models\GenerateExcelModel',
    ];

    public function actionT(){
        GenerateExcelModel::generateAreaInfo();
    }

    public function actionCancelOrders(){
        $ordersId = Yii::$app->RQ->AR(new OrderAR)->column([
            'select' => ['id'],
            'where' => [
                'status' => 4,
            ],
        ]);
        $title = ['订单号', '下单时间', '取消时间', '门店账号', '收货人姓名', '收货人地址', '收货人手机', '商品名称', '商品数量', '商品单价'];
        $rows = [];
        foreach($ordersId as $orderId){
            $order = new Order(['id' => $orderId]);
            foreach($order->items as $k => $item){
                if(!$k){
                    $rows[] = [
                        $order->orderNo,
                        $order->createTime,
                        $order->cancelTime,
                        $order->customerAccount,
                        $order->consignee,
                        $order->address,
                        $order->mobile,
                        $item->title,
                        $item->count,
                        $item->price,
                    ];
                }else{
                    $rows[] = [
                        '',//orderNo
                        '',//createTime,
                        '',//cancelTime,
                        '',//customerAccount
                        '',//consignee
                        '',//address
                        '',//mobile
                        $item->title,
                        $item->count,
                        $item->price,
                    ];
                }
            }
            unset($order);
        }
        ExcelHandler::output($rows, $title, '已取消订单信息-截止' . date('Y-m-d His'));
    }

    private $_rows = [];

    public function actionTree(){
        ini_set('memory_limit', '8192M');
        set_time_limit(60);
        $title = ['省级', '省级用户', '市级', '市级用户', '运营商', '运营商用户', '督导区', '督导区用户', '小组', '小组用户', '门店用户', '注册时间'];
        $parentId = 0;
        $this->generateNextLevelData($parentId);
        ExcelHandler::output($this->_rows, $title, '体系结构');
    }

    private function generateNextLevelData($parentId){
        $data = Yii::$app->RQ->AR(new \common\ActiveRecord\BusinessAreaAR)->all([
            'select' => ['id', 'name', 'level'],
            'where' => ['parent_business_area_id' => $parentId],
        ]);
        $row = [];
        foreach($data as $d){
            $step = 1;
            while($step < $d['level']){
                $row[] = '';
                $row[] = '';
                ++$step;
            }
            $row[] = $d['name'];
            $this->_rows[] = $row;
            unset($row);
            $this->generateBusinessUserData($d['id'], $d['level']);
            if($d['level'] < 5)$this->generateNextLevelData($d['id']);
            if($d['level'] == '5')$this->generateCustomUserData($d['id']);
        }
        unset($data);
    }

    private function generateBusinessUserData($id, $level){
        $data = Yii::$app->RQ->AR(new \common\ActiveRecord\BusinessUserAR)->column([
            'select' => ['account'],
            'where' => ['business_area_id' => $id],
        ]);
        if($data){
            foreach($data as $d){
                $step = 1;
                $row = [];
                $row[] = '';
                while($step < $level){
                    $row[] = '';
                    $row[] = '';
                    ++$step;
                }
                $row[] = $d;
                $this->_rows[] = $row;
                unset($row);
            }
        }
        unset($data);
    }

    private function generateCustomUserData($id){
        $data = Yii::$app->RQ->AR(new \common\ActiveRecord\CustomUserAR)->column([
            'select' => ['account'],
            'where' => [
                'business_area_id' => $id,
                'status' => 0,
                'authorized' => 1,
            ],
        ]);
        foreach($data as $d){
            $registerTime = Yii::$app->RQ->AR(new CustomUserRegistercodeAR)->scalar([
                'select' => ['register_time'],
                'where' => [
                    'account' => $d,
                ],
            ]);
            $this->_rows[] = ['', '', '', '', '', '', '', '', '', '', $d, $registerTime];
        }
        unset($data);
    }

    public function actionOrder(){
        $ordersId = Yii::$app->RQ->AR(new OrderAR)->column([
            'select' => ['id'],
            'where' => [
                'status' => 1,
            ],
        ]);
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header('Content-Disponsition:attachment;filename=order.txt');
        header("Pragma:no-cache");
        header("Expires:0");
        echo "订单号\t订单总价\t账号\t付款时间\t收货人姓名\t收货地址\t手机\t邮政编码\t商品名称\t商品属性\t商品数量\t商品总价\n";
        foreach($ordersId as $orderId){
            $quantity = 1;
            $order = new Order(['id' => $orderId]);
            echo "{$order->orderNo}\t{$order->totalFee}\t{$order->customerAccount}\t{$order->payTime}\t{$order->consignee}\t{$order->address}\t{$order->mobile}\t{$order->postalCode}\t";
            foreach($order->items as $item){
                if($quantity > 1){
                    echo " \t \t \t \t \t \t \t \t";
                }
                echo "{$item->title}\t";
                foreach($item->item->attributes as $attribute){
                    echo "{$attribute['name']} : {$attribute['selectedOption']['name']} ";
                }
                echo "\t";
                echo "{$item->count}\t{$item->totalFee}\t";
                echo "\n";
                $quantity++;
            }
        }
        exit;
    }

    public function actionZodiac(){
        $zodiacNumbers = Yii::$app->db->createCommand('select `zodiac`.`name`,`numb`.`num`,`user`.`account`,`user`.`id` from `pf_temp_youga_zodiac_number` as `numb` join `pf_temp_youga_zodiac` as `zodiac` on `numb`.`temp_youga_zodiac_id` = `zodiac`.`id` join `pf_custom_user` as `user` on `numb`.`custom_user_id` = `user`.`id` where `numb`.`selected` = 1')->queryAll();
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header('Content-Disponsition:attachment;filename=order.txt');
        header("Pragma:no-cache");
        header("Expires:0");
        echo "星座\t号码\t用户账号\t收货人\t收货地址\t手机\t默认地址\n";
        foreach($zodiacNumbers as $data){
            echo "{$data['name']}\t{$data['num']}\t{$data['account']}\t";
            $addressesId = Yii::$app->RQ->AR(new CustomUserAddressAR)->column([
                'select' => ['id'],
                'where' => [
                    'custom_user_id' => $data['id'],
                ],
            ]);
            $q = 1;
            if(empty($addressesId)){
                echo "\n";
                continue;
            }
            foreach($addressesId as $id){
                if($q > 1){
                    echo " \t \t \t";
                }
                $address = new Address(['id' => $id]);
                $fullAddress = strval($address);
                $default = $address->isDefault ? '是' : '否';
                echo "{$address->consignee}\t{$fullAddress}\t{$address->mobile}\t{$default}\n";
                $q++;
            }
        }
        exit;
    }

    public function actionUserDefaultAddress(){
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header('Content-Disponsition:attachment;filename=order.txt');
        header("Pragma:no-cache");
        header("Expires:0");
        echo "账号\t业务区域-省\t收货人\t省\t市\t区\t完整地址\t手机\n";
        $users = Yii::$app->RQ->AR(new CustomUserAR)->all([
            'select' => ['id', 'account', 'district_province_id'],
        ]);
        foreach($users as $user){
            echo "{$user['account']}\t";
            $Province = new Province(['provinceId' => $user['district_province_id']]);
            echo "{$Province->name}\t";
            $addressId = Yii::$app->RQ->AR(new CustomUserAddressAR)->scalar([
                'select' => ['id'],
                'where' => [
                    'custom_user_id' => $user['id'],
                    'default' => 1,
                ],
            ]);
            if($addressId){
                $address = new Address(['id' => $addressId]);
                $province = $address->getProvince(true);
                $city = $address->getCity(true);
                $district = $address->getDistrict(true);
                $fullAddress = strval($address);
                echo "{$address->consignee}\t{$province}\t{$city}\t{$district}\t{$fullAddress}\t{$address->mobile}";
            }
            echo "\n";
        }
        exit;
    }
}
