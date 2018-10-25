<?php
namespace common\models\temp\djy;

use Yii;
use yii\db\Query;
use common\ActiveRecord\BusinessAreaAR;
use common\models\parts\custom\CustomUser;
use common\components\handler\Handler;

class OrderStatistics extends Statistics{

    private $_commanderInfo = [];
    private $_allAreas;

    public function achieveList(array $params, int $currentPage = 1, int $pageSize = 10){
        $topAreaId = null;
        $secondaryAreaId = null;
        $tertiaryAreaId = null;
        $quaternaryAreaId = null;
        $account = null;
        extract($params, EXTR_IF_EXISTS);
        $totalCount = (new Query)->
            select('count(*)')->
            from(['item' => '{{%order_item}}'])->
            innerJoin(['order' => '{{%order}}'], '`item`.[[order_id]] = `order`.[[id]]')->
            innerJoin(['user' => '{{%custom_user}}'], '`item`.[[custom_user_id]] = `user`.[[id]]')->
            innerJoin(['record' => '{{%order_business_record}}'], '`order`.[[id]] = `record`.[[order_id]]')->
            filterWhere(['`record`.[[top_area_id]]' => $topAreaId])->
            andFilterWhere(['`record`.[[secondary_area_id]]' => $secondaryAreaId])->
            andFilterWhere(['`record`.[[tertiary_area_id]]' => $tertiaryAreaId])->
            andFilterWhere(['`record`.[[quaternary_area_id]]' => $quaternaryAreaId])->
            andFilterWhere(['`user`.[[account]]' => $account])->
            scalar();
        if(!$totalCount){
            return [
                'count' => 0,
                'total_count' => 0,
                'list' => [],
            ];
        }
        $query = (new Query)->
            select([
                'order_number' => '`order`.[[order_number]]',
                'custom_account' => '`user`.[[account]]',
                'custom_mobile' => '`user`.[[mobile]]',
                'sku_attributes' => '`item`.[[sku_attributes]]',
                'sku_quantity' => '`item`.[[count]]',
                'top_area_id' => '`record`.[[top_area_id]]',
                'secondary_area_id' => '`record`.[[secondary_area_id]]',
                'tertiary_area_id' => '`record`.[[tertiary_area_id]]',
                'quaternary_area_id' => '`record`.[[quaternary_area_id]]',
            ])->
            from(['item' => '{{%order_item}}'])->
            innerJoin(['order' => '{{%order}}'], '`item`.[[order_id]] = `order`.[[id]]')->
            innerJoin(['user' => '{{%custom_user}}'], '`item`.[[custom_user_id]] = `user`.[[id]]')->
            innerJoin(['record' => '{{%order_business_record}}'], '`order`.[[id]] = `record`.[[order_id]]')->
            filterWhere(['`record`.[[top_area_id]]' => $topAreaId])->
            andFilterWhere(['`record`.[[secondary_area_id]]' => $secondaryAreaId])->
            andFilterWhere(['`record`.[[tertiary_area_id]]' => $tertiaryAreaId])->
            andFilterWhere(['`record`.[[quaternary_area_id]]' => $quaternaryAreaId])->
            andFilterWhere(['`user`.[[account]]' => $account])->
            offset(($currentPage - 1) * $pageSize)->
            limit($pageSize)->
            all();
        $list = array_map(function($one){
            return array_merge(Handler::getMultiAttributes($one, [
                'order_number',
                'custom_account',
                'custom_mobile',
                'sku_attributes',
                'sku_quantity',
            ]), [
                'sku_attributes' => unserialize($one['sku_attributes']),
            ], [
                'area' => implode('/', array_map(function($areaId){
                    return $this->getAreaName($areaId);
                }, [
                    $one['top_area_id'],
                    $one['secondary_area_id'],
                    $one['tertiary_area_id'],
                    $one['quaternary_area_id'],
                ])),
            ], $this->getCommanderInfo($one['quaternary_area_id']));
        }, $query);
        return [
            'count' => count($query),
            'total_count' => $totalCount,
            'list' => $list,
        ];
    }

    protected function getCommanderInfo($quaternaryAreaId){
        if(!isset($this->_commanderInfo[$quaternaryAreaId])){
            $this->_commanderInfo[$quaternaryAreaId] = $this->achieveCommanderInfo($quaternaryAreaId);
        }
        return $this->_commanderInfo[$quaternaryAreaId];
    }

    protected function getAreaName($areaId){
        if(is_null($this->_allAreas)){
            $this->_allAreas = $this->achieveAllAreas();
        }
        return $this->_allAreas[$areaId] ?? '';
    }

    private function achieveAllAreas(){
        $result = BusinessAreaAR::find()->
            select(['id', 'name'])->
            where(['display' => 1])->
            all();
        return array_column($result, 'name', 'id');
    }

    private function achieveCommanderInfo($quaternaryAreaId){
        $emptyInfo = [
            'commander_name' => '',
            'commander_mobile' => '',
            'commander_address' => '',
        ];
        $quaternaryCommander = $this->getDjy()->getCommanders()->getQuaternaryAreaCommander();
        if($account = $quaternaryCommander[$quaternaryAreaId] ?? false){
            $user = new CustomUser([
                'account' => $account,
            ]);
            if($defaultAddress = $user->getDefaultAddress()){
                return [
                    'commander_name' => $defaultAddress->getConsignee(),
                    'commander_mobile' => $defaultAddress->getMobile(),
                    'commander_address' => strval($defaultAddress),
                ];
            }else{
                return $emptyInfo;
            }
        }else{
            return $emptyInfo;
        }
    }
}
