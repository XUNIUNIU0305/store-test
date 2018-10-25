<?php
namespace admin\modules\site\models;

use admin\components\handler\BusinessHandler;
use admin\models\parts\business\Business;
use common\components\handler\Handler;
use common\models\parts\business_area\AreaLeader;
use Yii;
use common\models\Model;

class BusinessModel extends Model
{
    const SCE_MODIFY_BUSINESS = 'modify_business';
    const SCE_REMOVE_BUSINESS = 'remove_business';
    const SCE_ADD_BUSINESS = 'add_business';
    const SCE_LIST_BUSINESS = 'get_list';
    const SCE_LEADER_BUSINESS = 'get_leader';
    const SCE_COMMISSAR_BUSINESS = 'get_commissar';

    //当前表主键id
    public $id;

    //当前修改区域
    public $area_id;

    //提交的数据
    public $set_business;

    //省
    public $top;

    //市
    public $secondary;

    //督导区
    public $tertiary;


    public function scenarios()
    {
        return [
            self::SCE_MODIFY_BUSINESS => [
                'id',
                'area_id',
                'set_business',
            ],
            self::SCE_REMOVE_BUSINESS => [
                'id',
                'area_id',
            ],
            self::SCE_ADD_BUSINESS => [
                'area_id',
                'set_business',
            ],

            self::SCE_LEADER_BUSINESS => [
                'area_id',
                'id',
            ],

            self::SCE_LIST_BUSINESS => [
                'top',
                'secondary',
                'tertiary',
            ],
            self::SCE_COMMISSAR_BUSINESS => [
                'id',
                'area_id',
            ],
        ];
    }

    public function rules()
    {
        return [
            [
                [
                    'id',
                    'set_business',
                    'area_id',
                ],
                'required',
                'message' => 9001,
            ],

        ];
    }

    //新增区域管理
    public function addBusiness()
    {
        if (BusinessHandler::create($this->area_id, $this->set_business))
        {
            return true;
        }
        else
        {
            $this->addError('addBusiness', 5184);
            return false;
        }
    }

    //删除区域管理
    public function removeBusiness()
    {
        if (BusinessHandler::delete($this->id, $this->area_id))
        {
            return true;
        }
        else
        {
            $this->addError('removeBusiness', 5185);
            return false;
        }
    }

    //修改区域管理
    public function modifyBusiness()
    {
        $business = new Business([
            'id' => $this->id,
            'area_id' => $this->area_id
        ]);
        if (in_array('business_area_leader_id', array_keys($this->set_business)))
        {
            $res = $business->setLeader($this->set_business['business_area_leader_id']);
        }
        elseif (in_array('commissar', array_keys($this->set_business)))
        {
            $res = $business->setCommissar($this->set_business['commissar']);
        }
        else
        {
            $res = $business->setTitle($this->set_business['title']);
        }
        if ($res !== false)
        {
            return true;
        }

        $this->addError('modifyBusiness', 5186);
        return false;

    }

    public function getList()
    {

        if ($this->top && !$this->secondary)
        {
            return BusinessHandler::getCity($this->top);

        }
        elseif ($this->secondary && $this->top && !$this->tertiary)
        {
            return BusinessHandler::getArea($this->top, $this->secondary);

        }
        elseif ($this->secondary && $this->top && $this->tertiary)
        {
            return BusinessHandler::getGroup($this->top, $this->secondary, $this->tertiary);
        }
        else
        {
            return BusinessHandler::getProvince();
        }
    }


    public function getLeader()
    {
        $leaderId = (new Business([
            'id' => $this->id,
            'area_id' => $this->area_id
        ]))->getLeaderId();

       return ['leader'=>(new AreaLeader(['id'=>$leaderId]))->getName()];

    }

    public function getCommissar(){
        $commissar = (new Business([
            'id' => $this->id,
            'area_id' => $this->area_id
        ]))->getCommissar();

        $leaderId = (new Business([
            'id' => $this->id,
            'area_id' => $this->area_id
        ]))->getLeaderId();


        return ['commissar'=>(new AreaLeader(['id'=>$commissar]))->getName(),'leader'=>(new AreaLeader(['id'=>$leaderId]))->getName()];
    }


}
