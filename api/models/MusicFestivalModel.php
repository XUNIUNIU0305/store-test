<?php
namespace api\models;

use Yii;
use common\models\Model;
use common\ActiveRecord\MusicFestivalAR;
use common\ActiveRecord\MusicFestivalAppointmentAR;
use common\models\parts\amap\Distance;
use common\components\handler\Handler;

class MusicFestivalModel extends Model{

    const SCE_GET_PERIPHERAL_STORE = 'get_peripheral_store';
    const SCE_APPOINTMENT = 'appointment';
    const SCE_CANCEL_APPOINTMENT = 'cancel_appointment';
    const SCE_GET_APPOINTMENT_INFO = 'get_appointment_info';

    public $longitude;
    public $latitude;
    public $radius;
    public $user_name;
    public $user_mobile;
    public $appointment_datetime;
    public $store_id;

    public function scenarios(){
        return [
            self::SCE_GET_PERIPHERAL_STORE => [
                'longitude',
                'latitude',
                'radius',
            ],
            self::SCE_APPOINTMENT => [
                'user_name',
                'user_mobile',
                'appointment_datetime',
                'store_id',
            ],
            self::SCE_CANCEL_APPOINTMENT => [
                'user_mobile',
            ],
            self::SCE_GET_APPOINTMENT_INFO => [
                'user_mobile',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['radius'],
                'default',
                'value' => 5000,
            ],
            [
                ['appointment_datetime'],
                'default',
                'value' => '0000-01-01 00:00:00',
            ],
            [
                ['store_id'],
                'default',
                'value' => 0,
            ],
            [
                ['longitude', 'latitude', 'radius', 'appointment_datetime', 'store_id', 'user_name', 'user_mobile'],
                'required',
                'message' => 9001,
            ],
            [
                ['user_name', 'user_mobile'],
                'trim',
            ],
            [
                ['longitude'],
                'double',
                'min' => -180,
                'max' => 180,
                'tooSmall' => 9002,
                'tooBig' => 9002,
                'message' => 9002,
            ],
            [
                ['latitude'],
                'double',
                'min' => -90,
                'max' => 90,
                'tooSmall' => 9002,
                'tooBig' => 9002,
                'message' => 9002,
            ],
            [
                ['radius'],
                'integer',
                'min' => 1,
                'max' => 10000,
                'tooSmall' => 9002,
                'tooBig' => 9002,
                'message' => 9002,
            ],
            [
                ['appointment_datetime'],
                '\api\validators\MusicFestival\AppointmentDatetimeValidator',
                'unavailableTime' => 7141,
                'message' => 9002,
            ],
            [
                ['store_id'],
                '\api\validators\MusicFestival\IdValidator',
                'message' => 9002,
            ],
            [
                ['user_mobile'],
                'integer',
                'min' => 10000000000,
                'max' => 19999999999,
                'tooSmall' => 7142,
                'tooBig' => 7142,
                'message' => 7142,
            ],
            [
                ['user_mobile'],
                '\api\validators\MusicFestival\UserMobileValidator',
                'message' => 7143,
                'on' => self::SCE_APPOINTMENT,
            ],
            [
                ['user_name'],
                'string',
                'length' => [1, 10],
                'tooShort' => 7144,
                'tooLong' => 7144,
                'message' => 9002,
            ],
        ];
    }

    public function getAppointmentInfo(){
        $appointmentData = MusicFestivalAppointmentAR::findOne([
            'user_mobile' => $this->user_mobile,
            'status' => 1,
        ]);
        if(!$appointmentData){
            return [
                'user_name' => '',
                'user_mobile' => '',
                'appointment_datetime' => '',
                'store_info' => false,
            ];
        }
        if($appointmentData->music_festival_id){
            $storeData = MusicFestivalAR::findOne($appointmentData->music_festival_id);
        }else{
            $storeData = false;
        }
        return Handler::getMultiAttributes($appointmentData, [
            'user_name',
            'user_mobile',
            'appointment_datetime',
            'store_info' => 'id',
            '_func' => [
                'appointment_datetime' => function($time){
                    return $time == '0000-01-01 00:00:00' ? '' : date('Y-m-d H:i', strtotime($time));
                },
                'id' => function($id)use($storeData){
                    if($storeData){
                        return Handler::getMultiAttributes($storeData, [
                            'store_id' => 'id',
                            'store_name',
                            'store_address',
                            'store_logo',
                            'store_contact',
                            'store_mobile',
                            'amap_location_longitude',
                            'amap_location_latitude',
                            '_func' => [
                                'store_logo' => function($logo){
                                    return Yii::$app->params['OSS_PostHost'] . '/' . $logo;
                                },
                                'amap_location_longitude' => function($longitude){
                                    return $longitude * 0.000001;
                                },
                                'amap_location_latitude' => function($latitude){
                                    return $latitude * 0.000001;
                                },
                            ],
                        ]);
                    }else{
                        return false;
                    }
                },
            ],
        ]);
    }

    public function cancelAppointment(){
        if($appointmentData = MusicFestivalAppointmentAR::findOne([
            'user_mobile' => $this->user_mobile,
            'status' => 1,
        ])){
            $appointmentData->status = 0;
            if($appointmentData->save()){
                return true;
            }else{
                $this->addError('cancelAppointment', 7146);
                return false;
            }
        }else{
            $this->addError('cancelAppointment', 7147);
            return false;
        }
    }

    public function appointment(){
        if($this->appointment_datetime == '0000-01-01 00:00:00' || $this->store_id == 0){
            $this->appointment_datetime = '0000-01-01 00:00:00';
            $this->store_id = 0;
        }
        $result = Yii::$app->RQ->AR(new MusicFestivalAppointmentAR)->insert([
            'user_name' => $this->user_name,
            'user_mobile' => $this->user_mobile,
            'appointment_datetime' => $this->appointment_datetime,
            'music_festival_id' => $this->store_id,
            'status' => 1,
        ]);
        if($result){
            return true;
        }else{
            $this->addError('appointment', 7145);
            return false;
        }
    }

    public function getPeripheralStore(){
        $longitudeWidth = round($this->radius / 111319, 6);
        $latitudeHeight = round($this->radius / 95105, 6);
        $minLongitude = round($this->longitude - $longitudeWidth, 6);
        $minLatitude = round($this->latitude - $latitudeHeight, 6);
        $maxLongitude = round($this->longitude + $longitudeWidth, 6);
        $maxLatitude = round($this->latitude + $latitudeHeight, 6);
        $availableStores = MusicFestivalAR::find()->
            select(['id', 'amap_location_longitude', 'amap_location_latitude'])->
            where(['>=', 'amap_location_longitude', $minLongitude * 1000000])->
            andWhere(['>=', 'amap_location_latitude', $minLatitude * 1000000])->
            andWhere(['<=', 'amap_location_longitude', $maxLongitude * 1000000])->
            andWhere(['<=', 'amap_location_latitude', $maxLatitude * 1000000])->
            asArray()->
            all();
        if(!$availableStores){
            return [
                'stores' => [],
                'total_count' => 0,
            ];
        }
        $origins = implode('|', array_map(function($location){
            return (string)($location['amap_location_longitude'] * 0.000001) . ',' . (string)($location['amap_location_latitude'] * 0.000001);
        }, $availableStores));
        $distance = new Distance([
            'origins' => $origins,
            'destination' => $this->longitude . ',' . $this->latitude,
        ]);
        if(!$distanceResult = $distance->achieve(false)){
            return [
                'stores' => [],
                'total_count' => 0,
            ];
        }
        $stores = [];
        foreach($distanceResult['results'] as $result){
            if($result['distance'] <= $this->radius){
                $stores[] = [
                    'id' => $availableStores[$result['origin_id'] - 1]['id'],
                    'distance' => $result['distance'],
                ];
            }
        }
        usort($stores, function($a, $b){
            return $a['distance'] <=> $b['distance'];
        });
        return [
            'stores' => array_map(function($store){
                return Handler::getMultiAttributes($store, [
                    'store_info' => 'id',
                    'distance',
                    '_func' => [
                        'id' => function($id){
                            $data = MusicFestivalAR::find()->
                                select([
                                    'id',
                                    'store_name',
                                    'store_address',
                                    'store_logo',
                                    'amap_location_longitude',
                                    'amap_location_latitude',
                                ])->
                                where(['id' => $id])->
                                asArray()->
                                one();
                            return Handler::getMultiAttributes($data, [
                                'store_id' => 'id',
                                'store_name',
                                'store_address',
                                'store_logo',
                                'amap_location_longitude',
                                'amap_location_latitude',
                                '_func' => [
                                    'store_logo' => function($logo){
                                        return Yii::$app->params['OSS_PostHost'] . '/' . $logo;
                                    },
                                    'amap_location_longitude' => function($longitude){
                                        return $longitude * 0.000001;
                                    },
                                    'amap_location_latitude' => function($latitude){
                                        return $latitude * 0.000001;
                                    },
                                ],
                            ]);
                        },
                    ],
                ]);
            }, $stores),
            'total_count' => count($stores),
        ];
    }
}
