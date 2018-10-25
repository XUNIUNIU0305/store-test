<?php
namespace console\models\groupbuy;

use yii\console\Controller as ConsoleController;
use console\models\groupbuy\exceptions;
use yii\helpers\Console;
use admin\models\parts\trade\Wallet as AdminWallet;
use custom\models\parts\trade\Wallet as CustomWallet;
use common\models\parts\trade\WalletAbstract;
use common\models\temp\Groupbuy;

use common\ActiveRecord\ProductAR;
use common\ActiveRecord\OrderAR;
use common\ActiveRecord\CustomUserAR;
use common\ActiveRecord\ActivityGroupbuyAR;
use common\ActiveRecord\ActivityGroupbuyLogAR;
use common\ActiveRecord\ActivityGroupbuyOrderAR;
use common\ActiveRecord\ActivityGroupbuyPriceAR;
use common\ActiveRecord\DistrictProvinceAR;
use common\ActiveRecord\DistrictCityAR;
use common\ActiveRecord\DistrictDistrictAR;

use Yii;

class CashBack
{
    private $db;
    private $logger;
    
    public function setDb($db)
    {
        $this->db = $db;
        return $this;
    }
    
    public function setLogger($logger)
    {
        $this->logger = $logger;
        return $this;
    }
    
    public function cashback($output)
    {
        if(! ($output instanceof ConsoleController)) {
            throw new exceptions\InvalidArgumentException(sprintf('内部错误, 在%s中.', __METHOD__));
        }
        
        if(empty($this->db) || ! ($this->db instanceof GroupbuyOrder)) {
            throw new exceptions\InvalidArgumentException(sprintf('数据库操作未注入, 在%s中.', __METHOD__));
        }
        
        
        $orders = $this->db->getAllOrderToRefund();
        
        if(empty($orders)) {
            $output->stdout("没有找到需要返现的订单\n");
            return true;
        }
        
        $success = 0;
        $noNeeds = 0;
        $failure = [];
        foreach($orders as $order) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $adminWallet = new AdminWallet();
                $price = $this->db->getOrderPrice($order['order_id']);
                $refundMount = $this->getRefund($price);
                if(!$productOrder = OrderAR::findOne($order['order_id'])) {
                    throw new Exception("订单不存在");
                }
                if($productOrder->total_fee <= $refundMount) {
                    throw new \Exception("订单金额大于或等于返现金额");
                }
                if($refundMount === 0) {
                    $this->db->markRefunded($order['id'], 0);
                    $transaction->commit();
                    $noNeeds++;
                    $this->db->comment($order['id'], sprintf("成功!订单:%s,用户:%s,无需返现.", $order['order_id'], $order['custom_user_id'], $refundMount));
                    $output->stdout(sprintf("成功!订单:%s,用户:%s,无需返现.\n", $order['order_id'], $order['custom_user_id'], $refundMount), Console::FG_GREY);
                    continue;
                } else {
                    $groupbuyRefund = new Groupbuy([
                        'id' => $order['id'],
                        'price' => $refundMount,
                        'order_id' => $order['order_id'],
                    ]);
                    $customWallet = new CustomWallet([
                        'userId' => $order['custom_user_id'],
                        'receiveType' => WalletAbstract::RECEIVE_GROUPBUY,
                    ]);
                    $result = $adminWallet->pay($groupbuyRefund, $customWallet);
                    $this->db->markRefunded($order['id'], $refundMount);
                    $this->db->comment($order['id'], sprintf("成功!订单:%s,用户:%s,获得返现%.2f元.", $order['order_id'], $order['custom_user_id'], $refundMount));
                    $transaction->commit();
                    $output->stdout(sprintf("成功!订单:%s,用户:%s,获得返现%.2f元.\n", $order['order_id'], $order['custom_user_id'], $refundMount), Console::FG_GREY);
                    $success++;
                }
            } catch(\Exception $ex) {
                $transaction->rollback();
                $failure[] = [$order['order_id'], $ex->getMessage()];
                $this->db->comment($order['id'], sprintf("失败!订单ID为:%d,用户:%s,原因:%s.", $order['order_id'], $order['custom_user_id'], $ex->getMessage()));
                $output->stderr(sprintf("失败!订单:%d,用户:%s,原因:%s.\n", $order['order_id'], $order['custom_user_id'], $ex->getMessage()), Console::FG_RED);
                continue;
            }
        }
        if(!empty($failure)) {
            $failString = '';
            foreach($failure as $fail) {
                $failString .= ($fail[0] . ',');
            }
            $failString .= "\n";
            $output->stderr(sprintf("以下订单返现失败:\n%s", $failString), Console::FG_RED);
        }
        $output->stdout(sprintf("返现结束,成功%d条,无需返现%d条,失败%d条.\n", $success, $noNeeds, $failure), Console::FG_GREEN);
        return true;
    }
    
    public function getRefund($prices)
    {
        $refund = 0;

        if(!is_array($prices)) {
            return $refund;
        }
        
        foreach($prices as $price) {
            $currentPrice = $this->db->getCurrentPrice($price['product_sku_id']);
            if($currentPrice['final_price'] == -1) {
                continue;
            }
            if(!$currentPrice) {
                continue;
            }
            $refund += ($price['price'] - $currentPrice['final_price']) * $price['count'];
        }
        
        if($refund >= 0) {
            return $refund;
        } else {
            return 0;
        }
    }
    
    public function export($output)
    {
        try {
            $cashbackOrders = ActivityGroupbuyOrderAR::find()->all();
            $excelRows = [];
            foreach($cashbackOrders as $cashbackOrder){
                $order = OrderAR::findOne($cashbackOrder->order_id);
                $custom = CustomUserAR::findOne($cashbackOrder->custom_user_id);
                if($custom) {
                    $province = DistrictProvinceAR::findOne($custom->district_province_id);
                    $city = DistrictCityAR::findOne($custom->district_city_id);
                    $district = DistrictDistrictAR::findOne($custom->district_district_id);
                    $loc = (isset($province->name) ? $province->name : '') 
                            . '/' . (isset($city->name) ? $city->name : '')
                            . '/' . (isset($district->name) ? $district->name : '');
                }
                $excelRows[] = [
                    $cashbackOrder->id ?? '未定义',
                    $custom->account ?? '未定义',
                    $loc ?? '未定义',
                    $order->order_number ?? '未定义',
                    $order->total_fee ?? '未定义',
                    $cashbackOrder->cash_back_amount ?? '未定义',
                    $order->coupon_rmb ?? '未定义',
                    $order->status ?? '未定义',
                    $order->create_datetime ?? '未定义',
                    $order->pay_datetime ?? '未定义',
                    $cashbackOrder->cash_back_datetime ?? '未定义',
                    $cashbackOrder->comment ?? '未定义',
                ];
            }
            $rows = $excelRows; 
            $title = [
                '序号',
                '客户账号',
                '所在地区',
                '订单号',
                '订单实付',
                '拼团返现',
                '订单优惠减免',
                '订单状态',
                '创建时间',
                '付款时间',
                '返现时间',
                '备注',
            ];
            $filename = '拼团活动详情' . date('Y_m_d_H_i_s') . '.xlsx';    
            if(count($title) > 26)throw new \Exception('too much columns');
            $filename = empty($filename) ? 'default' : $filename;
            $rowName = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
            $excel = new \PHPExcel;
            $excel->setActiveSheetIndex(0);
            $n = 0;
            $activeSheet = $excel->getActiveSheet();
            if(!empty($title)){
                $rowNum = 1;
                foreach($title as $name){
                    $activeSheet->setCellValue($rowName[$n] . $rowNum, $name);
                    ++$n;
                }
            }
            $rowNum = empty($title) ? 1 : 2;
            foreach($rows as $row){
                $n = 0;
                if(!is_array($row))throw new \Exception('data must be array');
                if(count($row) > 26)throw new \Exception('too much columns');
                foreach($row as $rowData){
                    $activeSheet->setCellValue($rowName[$n] . $rowNum, $rowData);
                    ++$n;
                }
                ++$rowNum;
            }
            $excelWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $excelWriter->save($filename);           
        } catch(\Exception $ex) {
            $output->stderr(sprintf("拼团活动详情导出失败,原因:%s.file:%s line(%d)\n", $ex->getMessage(), $ex->getFile(), $ex->getLine()), Console::FG_RED);
            return false;
        }
        $output->stdout(sprintf("拼团活动详情文件 [%s] 导出到 %s 成功.\n", $filename, dirname(dirname(dirname(__DIR__)))), Console::FG_GREEN);
        return true;
    }   
}
