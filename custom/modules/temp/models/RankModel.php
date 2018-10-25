<?php
namespace custom\modules\temp\models;

use Yii;
use common\models\Model;

class RankModel extends Model{

    const SCE_GET_RANK = 'get_rank';
    const SCE_GET_USER_RANK = 'get_user_rank';

    public function scenarios(){
        return [
            self::SCE_GET_RANK => [],
            self::SCE_GET_USER_RANK => [],
        ];
    }

    public function getRank(){
        if($rank = Yii::$app->cache->get('activity_rank')){
            $rank['data'] = $rank['toprank'];
            unset($rank['toprank']);
            unset($rank['allrank']);
            return $rank;
        }else{
            return [];
        }
    }

    public function getUserRank(){
        if(Yii::$app->user->isGuest){
            return [
                'rank' => 0,
            ];
        }else{
            if($rank = Yii::$app->cache->get('activity_rank')){
                if(array_key_exists(Yii::$app->user->id, $rank['allrank'])){
                    return $rank['allrank'][Yii::$app->user->id];
                }else{
                    return [
                        'rank' => 0,
                    ];
                }
            }else{
                return [
                    'rank' => 0,
                ];
            }
        }
    }
}
