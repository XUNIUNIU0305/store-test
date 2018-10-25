<?php
namespace api\models;

use Yii;
use common\models\Model;
use common\ActiveRecord\BusinessTopAreaAR;
use common\ActiveRecord\BusinessSecondaryAreaAR;
use common\ActiveRecord\BusinessTertiaryAreaAR;
use common\ActiveRecord\BusinessQuaternaryAreaAR;
use common\ActiveRecord\CustomUserRegistercodeAR;
use common\models\parts\business_area\TopArea;
use common\models\parts\business_area\SecondaryArea;
use common\models\parts\business_area\TertiaryArea;
use common\models\parts\business_area\QuaternaryArea;
use custom\models\parts\RegisterCode;

class BusinessareaModel extends Model{

    public $account;
    public $top_area;
    public $secondary_area;
    public $tertiary_area;

    const SCE_GET_AREA_LIST = 'get_area_list';

    public function scenarios(){
        return [
            self::SCE_GET_AREA_LIST => [
                'account',
                'top_area',
                'secondary_area',
                'tertiary_area',
            ],
        ];
    }

    public function rules(){
        return [
            /*取消读取运营商区域信息时，验证account信息
            [
                ['account'],
                'required',
                'message' => 9001,
            ],
            [
                ['account'],
                'exist',
                'targetClass' => CustomUserRegistercodeAR::className(),
                'targetAttribute' => ['account' => 'account', 'used' => 'used'],
                'message' => 7032,
            ],*/
            [
                ['top_area'],
                'exist',
                'targetClass' => BusinessTopAreaAR::className(),
                'targetAttribute' => 'id',
                'message' => 7031,
            ],
            [
                ['secondary_area'],
                'exist',
                'targetClass' => BusinessSecondaryAreaAR::className(),
                'targetAttribute' => [
                    'top_area' => 'business_top_area_id',
                    'secondary_area' => 'id',
                ],
                'message' => 7031,
            ],
            [
                ['tertiary_area'],
                'exist',
                'targetClass' => BusinessTertiaryAreaAR::className(),
                'targetAttribute' => [
                    'top_area' => 'business_top_area_id',
                    'secondary_area' => 'business_secondary_area_id',
                    'tertiary_area' => 'id',
                ],
                'message' => 7031,
            ],
        ];
    }

    public function getAreaList(){
        if($this->secondary_area || $this->tertiary_area){
            if($this->secondary_area && !$this->tertiary_area){
                $tertiaryAreaList = (new TertiaryArea(['secondaryId' => $this->secondary_area]))->list;
                return array_map(function($area){
                    return [
                        'id' => $area['id'],
                        'name' => $area['title'],
                        'title' => (new TertiaryArea(['tertiaryId' => $area['id']]))->leader->name,
                    ];
                }, $tertiaryAreaList);
            }else{
                $quaternaryAreaList = (new QuaternaryArea(['tertiaryId' => $this->tertiary_area]))->list;
                return array_map(function ($area)
                {
                    $quaternaryArea = new QuaternaryArea(['quaternaryId' => $area['id']]);
                    return [
                        'id' => $area['id'],
                        'name' => $area['title'],
                        'title' => $quaternaryArea->leader->name . '/' . $quaternaryArea->commissarName,
                    ];
                }, $quaternaryAreaList);
            }
        }elseif($this->top_area){
            return (new SecondaryArea(['topId' => $this->top_area]))->list;
        }else{
            return (new TopArea)->list;
        }
    }

    protected function getUsed(){
        return RegisterCode::STATUS_UNUSED;
    }
}
