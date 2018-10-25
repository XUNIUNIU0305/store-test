<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-10
 * Time: 下午2:35
 */

namespace common\models\lottery;


use yii\db\ActiveRecord;
use yii\db\ActiveRecordInterface;

class Object extends \yii\base\Object
{
    /**
     * @var ActiveRecord
     */
    protected $AR;

    public function __get($name)
    {
        try{
            return $this->AR->$name;
        } catch (\Exception $e){
            return parent::__get($name);
        }
    }

    /**
     * @param ActiveRecordInterface $ar
     */
    public function setAr(ActiveRecordInterface $ar)
    {
        $this->AR = $ar;
    }

    public function getAr()
    {
        return $this->AR;
    }

    public function getAttributes($names = null)
    {
        return $this->AR->getAttributes($names);
    }
}