<?php
namespace admin\modules\site\models\floor;
//楼层

use common\ActiveRecord\AdminFloorAR;
use Yii;
use yii\base\Object;
use admin\modules\site\models\FloorModel;

class Floor extends  Object {
    const HAS_COUNT_ERROR = 1;
    const HAS_NAME_ERROR = 2;
    
    public $id;//楼层id
    protected $name;//名称
    protected $url;//楼层链接
    protected $color;//楼层主色
    protected $type;//楼层主色

    public function __construct(FloorBuilder $builder){
        parent::__construct();
        $this->id = $builder->id;
        $this->name = $builder->name;
        $this->url = $builder->url;
        $this->color = $builder->color;
        $this->type = $builder->type;
        if ($this->id){
            $originName = $this->getOriginName();
            if($originName == $this->name) {
                if(current(AdminFloorAR::find()->select(['count(*) as count'])->where(['name' => $this->name])->asArray()->all())['count'] >= 2) {
                    throw new \Exception("", self::HAS_NAME_ERROR);
                }
            } else {
                if(current(AdminFloorAR::find()->select(['count(*) as count'])->where(['name' => $this->name])->asArray()->all())['count'] >= 1) {
                    throw new \Exception("", self::HAS_NAME_ERROR);
                }                
            }
            if(AdminFloorAR::findOne($this->id)->updateAttributes([
                'name'=>$this->name,
                'url'=>$this->url,
                'color'=>$this->color,
                'type'=>$this->type,
            ]) ===false ){
                throw new \Exception();
            }
        }else{
            if(current(AdminFloorAR::find()->select(['count(*) as count'])->asArray()->all())['count'] >= FloorModel::MAX_FLOOR_COUNT) {
                throw new \Exception("", self::HAS_COUNT_ERROR);
            }
            if(AdminFloorAR::findOne(['name' => $this->name])) {
                throw new \Exception("", self::HAS_NAME_ERROR);
            }
            
            $adminFloorAR = new AdminFloorAR();

            try {
                $adminFloorAR->insert();
                $this->id = Yii::$app->db->lastInsertID;
                $name = $this->getUniqueName();
                AdminFloorAR::findOne($this->id)->updateAttributes([
                    'name'=>$name,
                ]);
            } catch (\Exception $ex) {
                throw new \Exception();
            }
        }
    }
    
    public function getUniqueName()
    {
        $uniqueName = $this->id;
        while(AdminFloorAR::findOne(['name' => $uniqueName])) {
            $uniqueName++;
        }
        return $uniqueName;
    }
    
    public function getOriginName()
    {
        return AdminFloorAR::findOne($this->id)->name;
    }
}
