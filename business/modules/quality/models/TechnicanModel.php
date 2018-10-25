<?php
/**
 * User: JiangYi
 * Date: 2017/5/28
 * Time: 16:05
 * Desc:
 */

namespace business\modules\quality\models;


use business\models\parts\Area;
use business\models\parts\Role;
use common\ActiveRecord\BusinessAreaAR;
use common\ActiveRecord\BusinessAreaTechnicanAR;
use common\components\handler\Handler;
use common\components\handler\quality\BusinessAreaTechnicanHandler;
use common\models\Model;
use common\models\parts\quality\BusinessAreaTechnican;
use Yii;

class TechnicanModel extends  Model
{


    const SCE_GET_LIST="get_list";//获取技师列表
    const SCE_REMOVE="remove";//删除技师信息
    const SCE_SAVE='save';//修改
    const SCE_ADD="add";//添加


    public $current_page;
    public $page_size;
    public $area_id;

    public $name;
    public $mobile;
    public $remark;

    public $id;


    public function scenarios()
    {
       return [
           self::SCE_GET_LIST=>['page_size','current_page','area_id'],
           self::SCE_REMOVE=>['id'],
           self::SCE_SAVE=>['id','name','mobile','remark','area_id'],
           self::SCE_ADD=>['name','mobile','remark','area_id'],
       ];
    }

    public function rules()
    {
        return [
            [
                ['page_size'],
                'default',
                'value'=>10,
            ],
            [
                ['current_page'],
                'default',
                'value'=>1,
            ],
            [
                ['area_id'],
                'default',
                'value'=>0,
            ],
            [
                ['remark'],
                'default',
                'value'=>'',
            ],
            [
                ['area_id','current_page','page_size','name','mobile'],
                'required',
                'message'=>9001,
            ],
            [
                ['id'],
                'required',
                'message'=>9001,
                'on'=>[self::SCE_SAVE],
            ],
            [
                ['id'],
                'exist',
                'targetClass'=>BusinessAreaTechnicanAR::className(),
                'targetAttribute'=>['id'=>'id'],
                'message'=>13302,
            ], 

            [
                ['area_id'],
                'exist',
                'targetClass'=>BusinessAreaAR::className(),
                'targetAttribute'=>['area_id'=>'id'],
                'message'=>13301,
                'on'=>[self::SCE_ADD],
            ],

            [
                ['mobile'],
                'integer',
                'min' => 10000000000,
                'max' => 19999999999,
                'tooSmall' => 3166,
                'tooBig' => 3166,
                'message' => 3166,
            ],
            [
                ['name'],
                'string',
                'length'=>[1,12],
                'tooLong'=>13304,
                'tooShort'=>13304,
            ],
            [
                ['remark'],
                'string',
                'length'=>[0,255],
                'tooLong'=>13305,
                'tooShort'=>13305,
            ],

        ];
    }


    public function add(){
        if (Yii::$app->BusinessUser->account->role->id != Role::QUATERNARY){
            $this->addError('createOrder', 7100);
            return false;
        }
        if(BusinessAreaTechnicanHandler::create(new Area(['id'=>$this->area_id]),$this->name,$this->mobile,$this->remark)){
            return true;
        }
        $this->addError('add',13307);
        return false;

    }


    /**
     * Author:JiangYi
     * Date:2017/5/28
     * Desc:修改技师信息
     * @return bool
     */
    public function save(){
        if (Yii::$app->BusinessUser->account->role->id != Role::QUATERNARY){
            $this->addError('createOrder', 7100);
            return false;
        }

        $area =$this->area_id>0?new Area(['id'=>$this->area_id]):null;

        if((new BusinessAreaTechnican(['id'=>$this->id]))->setTechnicanInfo($this->name,$this->mobile,$this->remark,$area,false)!==false){
            return true;
        }
        $this->addError('save',13306);
        return false;
    }


    /**
     * Author:JiangYi
     * Date:2017/5/28
     * Desc:删除技师信息
     * @return bool
     */
    public function remove(){
        if (Yii::$app->BusinessUser->account->role->id != Role::QUATERNARY){
            $this->addError('createOrder', 7100);
            return false;
        }
        if(BusinessAreaTechnicanHandler::delete(new BusinessAreaTechnican(['id'=>$this->id])) !==false){
            return true;
        }
        $this->addError('remove',13303);
        return false;
    }

    /**
     * Author:JiangYi
     * Date:2017/5/28
     * Desc:获取技术列表
     * @return array
     */
    public function getList(){
        $area =$this->area_id>0?new Area(['id'=>$this->area_id]):null;

        $model=BusinessAreaTechnicanHandler::getList($this->page_size,$this->current_page,$area);
        $data= array_map(function($item){
            $item=new BusinessAreaTechnican(['id'=>$item['id']]);
            return [
                'id'=>$item->id,
                'name'=>$item->name,
                'mobile'=>$item->mobile,
                'remark'=>$item->remark,
                'area_id'=>$item->business_area_id,
            ];
        },$model->models);

        return [
            'count' => $model->count,
            'total_count' => $model->totalCount,
            'codes' => $data,
        ];
    }

}
