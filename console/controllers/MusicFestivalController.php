<?php
namespace console\controllers;

use Yii;
use console\controllers\basic\Controller;
use common\models\parts\amap\Geocode;
use common\ActiveRecord\MusicFestivalAR;

class MusicFestivalController extends Controller{

    public function actionAchieveGeocode(){
        $geocode = new Geocode;
        $emptyDatas = MusicFestivalAR::findAll([
            'amap_location_longitude' => 0,
            'amap_location_latitude' => 0,
        ]);
        foreach($emptyDatas as $data){
            if(!$data->store_address){
                $this->stdout("ID: [{$data->id}]无地址" . PHP_EOL);
                continue;
            }
            $geocode->address = $data->store_address;
            if($geoResult = $geocode->achieve(false)){
                $geoData = $geoResult['geocodes'][0];
                $data->amap_formatted_address = $geoData['formatted_address'];
                $data->amap_province = $geoData['province'];
                $data->amap_city = $geoData['city'];
                $data->amap_citycode = $geoData['citycode'];
                $data->amap_district = $geoData['district'];
                $data->amap_adcode = $geoData['adcode'];
                $data->amap_level = $geoData['level'];
                try{
                    list($data->amap_location_longitude, $data->amap_location_latitude) = array_map(function($v){
                        return $v * 1000000;
                    }, explode(',', $geoData['location']));
                }catch(\Exception $e){
                    $this->stdout("ID: [{$data->id}]生成经纬度失败" . PHP_EOL);
                    continue;
                }
                if($data->save()){
                    $this->stdout("ID: [{$data->id}]获取经纬度成功！" . PHP_EOL);
                }
            }else{
                $this->stdout("ID: [{$data->id}]获取经纬度失败" . PHP_EOL);
            }
        }
        $this->stdout('process done.' . PHP_EOL);
        return 0;
    }
}
