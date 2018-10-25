<?php
namespace console\controllers;

use Yii;
use console\controllers\basic\Controller;

class StatementController extends Controller{

    //protected $statementTable = 'pf_custom_user_statement';
    //protected $statementUserField = 'custom_user_id';
    //protected $userWalletTable = 'pf_custom_user_wallet';
    //protected $walletUserField = 'custom_user_id';
    //protected $receiveLogTable = 'pf_custom_user_receive_log';
    //protected $payLogTable = 'pf_custom_user_pay_log';

    //protected $statementTable = 'pf_admin_statement';
    //protected $statementUserField = 'admin_wallet_id';
    //protected $userWalletTable = 'pf_admin_wallet';
    //protected $walletUserField = 'id';
    //protected $receiveLogTable = 'pf_admin_receive_log';
    //protected $payLogTable = 'pf_admin_pay_log';

    //protected $statementTable = 'pf_supply_user_statement';
    //protected $statementUserField = 'supply_user_id';
    //protected $userWalletTable = 'pf_supply_user_wallet';
    //protected $walletUserField = 'supply_user_id';
    //protected $receiveLogTable = 'pf_supply_user_receive_log';
    //protected $payLogTable = 'pf_supply_user_pay_log';
    
    //protected $statementTable = 'pf_business_user_statement';
    //protected $statementUserField = 'business_user_id';
    //protected $userWalletTable = 'pf_business_user_wallet';
    //protected $walletUserField = 'business_user_id';
    //protected $receiveLogTable = 'pf_business_user_receive_log';
    //protected $payLogTable = 'pf_business_user_pay_log';

    public function actionReset($userId = null, $break = '1'){
        if(!$userId){
            $userIds = Yii::$app->db->createCommand("SELECT DISTINCT `{$this->statementUserField}` FROM `{$this->statementTable}`")->queryColumn();
        }else{
            if(Yii::$app->db->createCommand("SELECT `id` FROM `{$this->statementTable}` where `{$this->statementUserField}` = '{$userId}' LIMIT 1")->queryScalar()){
                $userIds = (array)$userId;
            }else{
                $this->stdout('no such user id');
                return 0;
            }
        }
        $userQuantity = 0;
        $fixId = [];
        foreach($userIds as $userId){
            do{
                $loop = true;
                $doFix = false;
                $statements = $this->getUserStatements($userId);
                foreach($statements as $key => $statement){
                    if(array_key_exists($key + 1, $statements)){
                        $nextKey = $key + 1;
                        if($statement['rmb_after'] == $statements[$nextKey]['rmb_before']){
                            continue;
                        }else{
                            $this->stdout("流水错误：前流水[id: {$statement['id']}, alteration_type: {$statement['alteration_type']}, alteration_amount: {$statement['alteration_amount']}, rmb_before: {$statement['rmb_before']}, rmb_after: {$statement['rmb_after']}]\n          后流水[id: {$statements[$nextKey]['id']}, alteration_type: {$statements[$nextKey]['alteration_type']}, alteration_amount: {$statements[$nextKey]['alteration_amount']}, rmb_before: {$statements[$nextKey]['rmb_before']}, rmb_after: {$statements[$nextKey]['rmb_after']}]\n");
                            $this->stdout("执行修正\n");
                            $diffRmb = sprintf("%.2f", $statement['rmb_after'] - $statements[$nextKey]['rmb_before']);
                            if(abs($diffRmb) != $statement['alteration_amount']){
                                $this->stdout("变动金额错误 [diffRmb: {$diffRmb},  alteration_amount: {$statement['alteration_amount']}]\n");
                                if($break){
                                    return 0;
                                }
                            }
                            $counterRmb = $diffRmb > 0 ? "+ {$diffRmb}" : '- ' . abs($diffRmb);
                            $transaction = Yii::$app->db->beginTransaction();
                            try{
                                Yii::$app->db->createCommand("SELECT * FROM `{$this->userWalletTable}` WHERE `{$this->walletUserField}` = {$userId} FOR UPDATE")->queryOne();
                                $statementFix = Yii::$app->db->createCommand("UPDATE `{$this->statementTable}` SET `rmb_before` = `rmb_before` {$counterRmb}, `rmb_after` = `rmb_after` {$counterRmb} WHERE `{$this->statementUserField}` = {$userId} AND `id` > {$statement['id']}")->execute();
                                var_dump($counterRmb);
                                $receiveFix = Yii::$app->db->createcommand("UPDATE `{$this->receiveLogTable}` AS `lt` INNER JOIN `{$this->statementTable}` AS `st` ON `st`.`corresponding_log_id` = `lt`.`id` SET `lt`.`rmb_before` = `st`.`rmb_before`,`lt`.`rmb_after` = `st`.`rmb_after` WHERE `st`.`alteration_type` = 1 AND `st`.`{$this->statementUserField}` = {$userId}")->execute();
                                $payFix = Yii::$app->db->createcommand("UPDATE `{$this->payLogTable}` AS `lt` INNER JOIN `{$this->statementTable}` AS `st` ON `st`.`corresponding_log_id` = `lt`.`id` SET `lt`.`rmb_before` = `st`.`rmb_before`,`lt`.`rmb_after` = `st`.`rmb_after` WHERE `st`.`alteration_type` = 2 AND `st`.`{$this->statementUserField}` = {$userId}")->execute();
                                $rmbFix = Yii::$app->db->createCommand("UPDATE `{$this->userWalletTable}` SET `rmb` = `rmb` {$counterRmb} WHERE `{$this->walletUserField}` = {$userId}")->execute();
                                $transaction->commit();
                                //var_dump($statementFix, $receiveFix, $payFix, $rmbFix);
                                $doFix = true;
                                $this->stdout("修正成功\n");
                            }catch(\Exception $e){
                                $transaction->rollBack();
                                $this->stdout("修正失败\n");
                            }
                            break;
                        }
                    }
                }
                if($doFix){
                    if(!in_array($userId, $fixId)){
                        $fixId[] = $userId;
                    }
                }
                $loop = $doFix ? true : false;
            }while($loop);
            ++$userQuantity;
            $this->stdout("用户ID: {$userId}，操作完毕({$userQuantity})\n\n");
        }
        $allFixId = implode(', ', $fixId);
        $this->stdout("已修正用户：{$allFixId}\n");
    }

    private function getUserStatements($userId){
        return Yii::$app->db->createCommand("SELECT `id`,`alteration_type`,`alteration_amount`,`rmb_before`,`rmb_after` FROM `{$this->statementTable}` WHERE `{$this->statementUserField}` = '{$userId}' ORDER BY `id` ASC")->queryAll();
    }
}
