<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/11/2
 * Time: 上午11:57
 */

namespace admin\modules\site\models\floor;


use common\ActiveRecord\AdminFloorGroupAR;
use yii\base\Object;
use Yii;

class Group extends Object
{
    const HAS_NAME_ERROR = 2;
    
    public $id;//组id
    public $name;//组名称

    /** 
     * 更新,添加楼层
     * 
     * 要是传入数据存在id字段,则修改.
     * 如果没有,则新增.
     */
    public function __construct(FloorBuilder $builder)
    {
        parent::__construct();
        $this->id = $builder->group_id;
        $this->name = $builder->group_name;
        if ($this->id){
            $origin = $this->getOrigin();
            if($origin->name == $this->name) {
                if(current(AdminFloorGroupAR::find()->select(['count(*) as count'])->where([
                    'name'      => $this->name,
                    'floor_id'  => $origin->floor_id
                ])->asArray()->all())['count'] >= 2) {
                    throw new \Exception("", self::HAS_NAME_ERROR);
                }
            } else {                
                if(current(AdminFloorGroupAR::find()->select(['count(*) as count'])->where([
                    'name'      => $this->name,
                    'floor_id'  => $origin->floor_id
                ])->asArray()->all())['count'] >= 1) {
                    throw new \Exception("", self::HAS_NAME_ERROR);
                }                
            }  
            if(AdminFloorGroupAR::findOne($this->id)->updateAttributes([
                'name'=>$this->name,
            ]) ===false ){
                throw new \Exception();
            }
        }else{
           
            $adminFloorGroupAR = new AdminFloorGroupAR();

            try {
                $adminFloorGroupAR->insert();
                $this->id = Yii::$app->db->lastInsertID;
                $this->name = $this->getUniqueName();
                AdminFloorGroupAR::findOne($this->id)->updateAttributes([
                    'name'=>$this->name,
                    'floor_id' => $builder->id,
                ]);
            } catch (\Exception $ex) {
                throw new \Exception();
            }
        }
    }

    /**
     * 获得唯一的name,算法是依次++name,直到获得唯一的name
     * 
     * @return string
     */
    public function getUniqueName()
    {
        $uniqueName = $this->id;
        while(AdminFloorGroupAR::findOne(['name' => $uniqueName])) {
            $uniqueName++;
        }
        return $uniqueName;
    }
    
    /**
     * 获得原始的条目.
     * 
     * @return static ActiveRecord instance matching the condition, or `null` if nothing matches.
     */
    public function getOrigin()
    {
        return AdminFloorGroupAR::findOne($this->id);
    }
}