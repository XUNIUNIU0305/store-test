<?php
namespace admin\models\parts;

use Yii;
use yii\base\Object;
use Curl\Curl;
use common\ActiveRecord\DistrictProvinceAR;
use common\ActiveRecord\DistrictCityAR;
use common\ActiveRecord\DistrictDistrictAR;
use common\models\RapidQuery;
use yii\base\InvalidCallException;

class District extends Object{

    public $request = [
        'url' => 'http://restapi.amap.com/v3/config/district',
        'key' => 'b85b72982ad806f3eee6e757da209aab',
        'params' => [
            'subdistrict' => 3,
            'showbiz' => false,
        ],
    ];

    protected $url;
    protected $params;

    public function init(){
        $request = $this->request;
        $this->url = $request['url'];
        $this->params = array_merge(['key' => $request['key']], $request['params']);
    }

    public function generate(){
        if(DistrictProvinceAR::find()->exists() ||
            DistrictCityAR::find()->exists() ||
            DistrictDistrictAR::find()->exists()
        )throw new InvalidCallException('The Database Table is not empty');
        if(!$districtData = $this->getDistrictData()){
            throw new \Exception('unable to get district data');
        }
        foreach($districtData as $province){
            $provinceId = $this->saveData([
                'name' => $province['name'],
                'citycode' => empty($province['citycode']) ? '' : $province['citycode'],
                'adcode' => $province['adcode'],
            ], $province['level']);
            foreach($province['districts'] as $city){
                if($city['level'] == 'city'){
                    $cityId = $this->saveData([
                        'name' => $city['name'],
                        'citycode' => $city['citycode'],
                        'adcode' => $city['adcode'],
                        'district_province_id' => $provinceId,
                    ], $city['level']);
                }else{
                    $this->saveData([
                        'name' => $city['name'],
                        'citycode' => $city['citycode'],
                        'adcode' => $city['adcode'],
                        'district_province_id' => $provinceId,
                        'district_city_id' => 0,
                    ], $city['level']);
                    continue;
                }
                foreach($city['districts'] as $district){
                    $this->saveData([
                        'name' => $district['name'],
                        'citycode' => $district['citycode'],
                        'adcode' => $district['adcode'],
                        'district_province_id' => $provinceId,
                        'district_city_id' => $cityId,
                    ], $district['level']);
                }
            }
        }
        return true;
    }

    protected function saveData($data, $level){
        switch($level){
            case 'province':
            case 'city':
            case 'district':
                $className = 'District' . ucfirst($level) . 'AR';
                $result = (new RapidQuery((new \ReflectionClass('\common\ActiveRecord\\' . $className))->newInstance()))->insert($data);
                break;

            case 'biz_area':
                $result = true;
                break;

            default:
                throw new \Exception('unknown level: ' . $level);
        }
        if(!$result)throw new \Exception('save district data failed');
        return Yii::$app->db->lastInsertId;
    }

    protected function getDistrictData(){
        $curl = new Curl();
        $curl->setDefaultJsonDecoder(true);
        $curl->get($this->url, $this->params);
        if($curl->error){
            return false;
        }else{
            $data = $curl->response;
            return $data['districts'][0]['districts'];
        }
    }
}
