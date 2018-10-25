<?php
/**
 * Created by PhpStorm.
 * User: forrest
 * Date: 25/05/18
 * Time: 10:42
 */

namespace console\controllers;

use common\ActiveRecord\QualityImportLogAR;
use common\models\parts\sms\SmsSender;
use console\controllers\basic\Controller;
use custom\models\parts\sms\Sms;

class QualityOrderSmsSendController extends Controller
{
    public function actionIndex()
    {
        return 0;
        $this->handler();
    }

    public function handler()
    {
        // 上次导入log的id
        $last_import_log_id = 28837;

        $tmpMobiles = [
            '15180628419', // 高忠欢
            '13817273572', // 陈英
            '13774292424', // 王沈峰
            '15221062486', // 朱圣杰
            '13429890785', // 杨曼玲
            '17317937115', // 刘程旭
            '18918553848', // 曹易文
            '15262207932', // 孙佳佳
        ];
        $mobiles = QualityImportLogAR::find()->select(['owner_mobile'])->distinct(['owner_mobile'])
            ->where('order_code != 0')->andWhere("id > $last_import_log_id")->column();
        $mobiles = array_merge($mobiles, $tmpMobiles);
        shuffle($mobiles);
        $datas = array_chunk($mobiles, 100);
        foreach ($datas as $key => $mobiles) {
            if ($this->sendSms($mobiles)) {
                echo "第 {$key} 批短信发送成功\n";
            } else {
                echo "第 {$key} 批短信发送失败\n";
            }
        }
    }

    /**
     * 批量发送短信，每次最多100条
     * @param array $mobiles
     * @return bool
     */
    public function sendSms(array $mobiles = [])
    {
        $smsSender = new SmsSender(['validateMobile' => false]);
        $sms = new Sms([
            'mobile' => $mobiles,
            'signName' => '九大爷平台',
            'templateCode' => 'SMS_135801204',
            'param' => [],
        ]);
        if ($smsSender->send($sms)) {
            return true;
        }
        return false;
    }
}