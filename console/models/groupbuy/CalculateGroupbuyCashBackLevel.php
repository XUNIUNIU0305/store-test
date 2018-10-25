<?php
namespace console\models\groupbuy;

use yii\console\Controller as ConsoleController;
use console\models\groupbuy\exceptions;
use yii\helpers\Console;
use Yii;

class CalculateGroupbuyCashBackLevel
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
    
    public function calculate($output)
    {
        if(! ($output instanceof ConsoleController)) {
            throw new exceptions\InvalidArgumentException(sprintf('内部错误, 在%s中.', __METHOD__));
        }
        
        if(empty($this->db) || ! ($this->db instanceof GroupbuyOrder)) {
            throw new exceptions\InvalidArgumentException(sprintf('数据库操作未注入, 在%s中.', __METHOD__));
        }

        //更新阶梯价到团购sku表
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $affectRowCount = $this->db->updateGroupbuyCurrentPrice();
            $transaction->commit();
        } catch (\Exception $ex) {
            $transaction->rollback();
            $output->stderr("团购sku更新失败.\n", Console::FG_RED);
            return false;
        }
        
        $output->stdout(sprintf("阶梯价格数据运算及更新成功,相关的团购价格记录%d条.\n", $affectRowCount), Console::FG_GREEN);
        
        return true;
    }
    
}