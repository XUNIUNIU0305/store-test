<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-24
 * Time: 上午10:43
 */

namespace business\modules\data\models\objects;


use business\models\parts\Area;
use common\ActiveRecord\BusinessAreaAR;
use yii\base\Model;
use yii\db\ActiveRecord;

class BusinessArea extends Model
{
    private $ar;

    public $id;
    public $name = '未定义区域';
    public $level = Area::LEVEL_UNDEFINED;
    public $parent_business_area_id;

    public function init()
    {
        if(!$this->ar && $this->id)
            $this->ar = BusinessAreaAR::findOne($this->id);
        if($this->ar instanceof ActiveRecord)
            $this->setAttributes($this->ar->getAttributes(), false);
    }
}