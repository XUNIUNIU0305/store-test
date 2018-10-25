<?php
namespace admin\models\parts\business;

use common\ActiveRecord\BusinessAreaLeaderAR;
use Yii;
use yii\base\Object;

class Employee extends Object
{
    public $id;
    private $AR;


    public function init()
    {
        if (!empty($this->id) && Yii::$app->RQ->AR(new BusinessAreaLeaderAR())->exists([
                'where' => [
                    'id' => $this->id,
                ]
            ])
        )
        {
            $this->AR = BusinessAreaLeaderAR::findOne(['id' => $this->id]);
        }

    }

    //更新数据
    public function setEmployee($employee)
    {
        if ($this->AR)
        {
            $this->AR->name = $employee['name'] ?? '';
            $this->AR->mobile = $employee['mobile'] ?? 0;
            $this->AR->remark = $employee['remark'] ?? '';
            return $this->AR->update();
        }
        return false;
    }

}
