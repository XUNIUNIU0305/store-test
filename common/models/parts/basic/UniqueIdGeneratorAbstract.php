<?php
namespace common\models\parts\basic;

use Yii;
use yii\base\Object;
use yii\base\InvalidConfigException;

abstract class UniqueIdGeneratorAbstract extends Object{

    const DATE_LENGTH = 5;

    public $length = 10;

    protected $randomIntLength;

    public function init(){
        $this->length = (int)$this->length;
        if($this->length < self::DATE_LENGTH)throw new InvalidConfigException('unique id must greater than 5');
        $this->randomIntLength = $this->length - self::DATE_LENGTH;
    }

    /**
     * 获取ActiveRecord对象
     *
     * @return ActiveRecord
     */
    abstract protected function getActiveRecord();

    /**
     * 获取数据表字段名称
     *
     * 主要为了确定不重复
     *
     * @return string
     */
    abstract protected function getFieldName();

    /**
     * 获取唯一ID
     *
     * @return string
     */
    public function getId(){
        do{
            $id = $this->generateId();
        }while($this->validateExist($id));
        return $id;
    }

    /**
     * 生成ID
     *
     * @return string
     */
    protected function generateId(){
        list($oneDigit, $restDigit) = $this->generateRandomInt();
        $fiveDate = $this->generateRandomDate();
        return ($oneDigit . $fiveDate . $restDigit);
    }

    /**
     * 生成随机日期
     *
     * @return string
     */
    protected function generateRandomDate(){
        $year = substr(Yii::$app->time->year, -1, 1);
        $month = $this->generateRandomMonth();
        $day = $this->generateRandomDay();
        return ($year . $month . $day);
    }

    /**
     * 生成随机-天
     *
     * @return string
     */
    protected function generateRandomDay(){
        $day = Yii::$app->time->day;
        switch(substr($day, 0, 1)){
            case '0':
                $tens = 0;
                break;

            case '1':
                $tens = rand(1, 3);
                break;

            case '2':
                $tens = rand(4, 6);
                break;

            case '3':
                $tens = rand(7, 9);
                break;

            default:
                throw new \Exception;
        }
        return ($tens . substr($day, 1));
    }

    /**
     * 生成随机-月
     *
     * @return string
     */
    protected function generateRandomMonth(){
        $month = strval(Yii::$app->time->month);
        switch(substr($month, 0, 1)){
            case '0':
                $tens = rand(0, 4);
                break;

            case '1':
                $tens = rand(5, 9);
                break;

            default:
                throw new \Exception;
        }
        return ($tens . substr($month, 1));
    }

    /**
     * 生成随机数字
     *
     * @return array
     */
    protected function generateRandomInt(){
        if($this->randomIntLength > 0){
            $minInt = '1';
            $maxInt = '9';
            for($i = 1; $i < $this->randomIntLength; $i++){
                $minInt .= '0';
                $maxInt .= '9';
            }
            $str = strval(rand(intval($minInt), intval($maxInt)));
            return [
                substr($str, 0, 1),
                substr($str, 1),
            ];
        }else{
            return [
                '',
                '',
            ];
        }
    }

    /**
     * 验证随机ID是否存在
     *
     * @return boolean
     */
    protected function validateExist($id){
        $ActiveRecord = $this->ActiveRecord;
        $fieldName = $this->fieldName;
        return $ActiveRecord::find()->select(['id'])->where([$fieldName => $id])->limit(1)->exists();
    }
}
