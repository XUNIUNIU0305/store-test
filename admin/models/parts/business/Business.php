<?php
namespace admin\models\parts\business;

use common\ActiveRecord\BusinessQuaternaryAreaAR;
use common\ActiveRecord\BusinessSecondaryAreaAR;
use common\ActiveRecord\BusinessTertiaryAreaAR;
use common\ActiveRecord\BusinessTopAreaAR;
use common\ActiveRecord\CustomUserAR;
use Yii;
use yii\base\Object;

class Business extends Object
{
    //省
    const PROVINCE = 1;
    //市
    const CITY = 2;
    //区
    const AREA = 3;
    //小组
    const GROUP = 4;

    public $id;
    public $area_id;
    private $AR;


    public function init()
    {
        if (empty($this->area_id))
        {
            return false;
        }

        switch ($this->area_id)
        {
            //top区域 省
        case self::PROVINCE:
            $this->AR = $this->id ? BusinessTopAreaAR::findOne(['id' => $this->id]) : new BusinessTopAreaAR();
            break;
            //市
        case self::CITY:
            $this->AR = $this->id ? BusinessSecondaryAreaAR::findOne(['id' => $this->id]) : new BusinessSecondaryAreaAR();
            break;
            //督导区
        case self::AREA:
            $this->AR = $this->id ? BusinessTertiaryAreaAR::findOne(['id' => $this->id]) : new BusinessTertiaryAreaAR();
            break;
            //小组
        case self::GROUP:
            $this->AR = $this->id ? BusinessQuaternaryAreaAR::findOne(['id' => $this->id]) : new BusinessQuaternaryAreaAR();
            break;
        }

    }

    public function getAR()
    {
        return $this->AR;
    }


    //设置title
    public function setTitle($title)
    {
        if (!$this->AR)return false;
        if ($this->area_id == self::PROVINCE && Yii::$app->RQ->AR($this->AR)->exists(['where' => ['title' => $title], 'limit' => 1]))return false;
        $this->AR->title = $title;
        return $this->AR->update();

    }

    public function setLeader($leader)
    {
        if (!$this->AR) return false;
        $this->AR->business_area_leader_id = $leader;
        return $this->AR->update();

    }

    public function setCommissar($commissar)
    {
        if (!$this->AR) return false;
        $this->AR->commissar = $commissar;
        return $this->AR->update();

    }


    //验证下一级是否存在 和custom_user表是否有记录
    public function isExist($id)
    {
        $nail = '';
        switch ($this->area_id - 1)
        {
        case self::PROVINCE:
            $nail = 'top';
            break;
        case self::CITY:
            $nail = 'secondary';
            break;
        case self::AREA:
            $nail = 'tertiary';
            break;
        case self::GROUP:
            $nail = 'quaternary';
            break;
        }



        $next = Yii::$app->RQ->AR($this->AR)->exists([
            'where' => [
                'business_' . $nail . '_area_id' => $id
            ],
            'limit' => 1,
        ]);


        $custom = Yii::$app->RQ->AR(new CustomUserAR())->exists([
            'where' => [
                'business_' . $nail . '_area_id' => $id
            ],
            'limit' => 1,
        ]);

        if ($next || $custom)
        {
            return false;
        }
        return true;

    }

    public function getLeaderId(){
        return $this->AR->business_area_leader_id;
    }

    public function getCommissar(){
        return $this->AR->commissar;
    }









}
