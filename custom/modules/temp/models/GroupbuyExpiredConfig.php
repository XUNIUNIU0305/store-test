<?php
namespace custom\modules\temp\models;

use yii\base\Object;

class GroupbuyExpiredConfig extends Object
{
    private $activity_datetime_start    = '2018-06-27 00:00:00';
    private $activity_datetime_finish   = '2018-06-29 23:59:59';
    private $start_timestamp;
    private $finish_timestamp;
    
    public function init()
    {
        /**
         * config file: groupbuy_config.php
         * ```php
         * return [
         *     'start' => `date`,
         *     'finish' => `date`,
         * ];
         * ```
         */
        try{
            if(is_file($configFile = __DIR__ . '/groupbuy_config.php')){
                $config = include($configFile);
                $this->start_timestamp = strtotime($config['start']);
                $this->finish_timestamp = strtotime($config['finish']);
            }else{
                throw new \Exception;
            }
        }catch(\Exception $e){
            $this->start_timestamp     = strtotime($this->activity_datetime_start);
            $this->finish_timestamp    = strtotime($this->activity_datetime_finish);
        }
    }
    
    public function isValid($timestamp)
    {
        if($timestamp > $this->finish_timestamp || $timestamp < $this->start_timestamp) {
            return false;
        }
        return true;
    }
    
    public function getTimestamp()
    {
        return [
            'start_at'  => $this->start_timestamp,
            'finish_at' => $this->finish_timestamp,
        ];
    }
}
