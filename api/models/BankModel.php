<?php
namespace api\models;

use Yii;
use common\models\Model;
use common\ActiveRecord\BankListAR;
use common\ActiveRecord\DistrictProvinceAR;
use common\ActiveRecord\DistrictCityAR;
use common\ActiveRecord\DistrictDistrictAR;
use common\components\handler\Handler;
use common\models\parts\trade\recharge\nanjing\bank\Bank;
use common\models\parts\trade\recharge\nanjing\bank\Code;

class BankModel extends Model{

    const SCE_GET_BANK_CODE = 'get_bank_code';

    public $bank_id;
    public $province_id;
    public $city_id;
    public $district_id;
    public $string;
    public $current_page;
    public $page_size;

    public function scenarios(){
        return [
            self::SCE_GET_BANK_CODE => [
                'bank_id',
                'province_id',
                'city_id',
                'district_id',
                'string',
                'current_page',
                'page_size',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['current_page'],
                'default',
                'value' => 1,
            ],
            [
                ['page_size'],
                'default',
                'value' => 20,
            ],
            [
                ['current_page', 'page_size'],
                'required',
                'message' => 9001,
            ],
            [
                ['bank_id'],
                'exist',
                'targetClass' => BankListAR::className(),
                'targetAttribute' => 'id',
                'message' => 9002,
            ],
            [
                ['province_id'],
                'exist',
                'targetClass' => DistrictProvinceAR::className(),
                'targetAttribute' => 'id',
                'message' => 9002,
            ],
            [
                ['city_id'],
                'exist',
                'targetClass' => DistrictCityAR::className(),
                'targetAttribute' => 'id',
                'message' => 9002,
            ],
            [
                ['district_id'],
                'exist',
                'targetClass' => DistrictDistrictAR::className(),
                'targetAttribute' => 'id',
                'message' => 9002,
            ],
            [
                ['string'],
                'string',
                'length' => [1, 100],
                'tooShort' => 9002,
                'tooLong' => 9002,
                'message' => 9002,
            ],
            [
                ['current_page', 'page_size'],
                'integer',
                'min' => 1,
                'max' => 200,
                'tooSmall' => 9002,
                'tooBig' => 9002,
                'message' => 9002,
            ],
        ];
    }

    public function getBankCode(){
        $code = new Code([
            'bank' => $this->bank_id,
            'provinceId' => $this->province_id,
            'cityId' => $this->city_id,
            'districtId' => $this->district_id,
            'string' => $this->string,
        ]);
        $list = $code->provide((int)$this->current_page, (int)$this->page_size);
        if($list){
            return Handler::getMultiAttributes($list, [
                'count',
                'total_count' => 'totalCount',
                'list' => 'models',
                '_func' => [
                    'totalCount' => function($totalCount){
                        if($totalCount > 200){
                            return 200;
                        }else{
                            return $totalCount;
                        }
                    },
                ],
            ]);
        }else{
            return [
                'count' => 0,
                'total_count' => 0,
                'list' => [],
            ];
        }
    }

    public static function getList(){
        return Bank::getList([
            'id',
            'name',
            'image',
        ]);
    }

    public static function getIdType(){
        return [
            ['id' => '1', 'name' => '二代居民身份证'],
            ['id' => '2', 'name' => '户口本'],
            ['id' => '3', 'name' => '护照'],
            ['id' => '4', 'name' => '港澳居民来往内陆通行证'],
            ['id' => '5', 'name' => '港澳同胞回乡证'],
            ['id' => '6', 'name' => '台湾居民来往大陆通行证'],
            ['id' => '7', 'name' => '其他有效证件'],
            ['id' => 'm', 'name' => '机构代码证'],
            ['id' => 'n', 'name' => '营业执照'],
            ['id' => 'p', 'name' => '登记证书'],
            ['id' => 'q', 'name' => '国税税务登记证号'],
            ['id' => 'r', 'name' => '地税税务登记证号'],
        ];
    }
}
