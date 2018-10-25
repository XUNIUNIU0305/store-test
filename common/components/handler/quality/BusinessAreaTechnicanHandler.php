<?php
/**
 * User: JiangYi
 * Date: 2017/5/28
 * Time: 15:39
 * Desc:
 */

namespace common\components\handler\quality;


use business\models\parts\Area;
use common\ActiveRecord\BusinessAreaTechnicanAR;
use common\components\handler\Handler;
use common\models\parts\quality\BusinessAreaTechnican;

use yii\data\ActiveDataProvider;
use Yii;

class BusinessAreaTechnicanHandler extends  Handler
{


    /**
     * Author:JiangYi
     * Date:2017/5/28
     * Desc:删除技师信息
     * @param Area $area
     * @param Technican $technican
     * @param string $return
     * @return mixed
     */
    public static function delete(BusinessAreaTechnican $technican){
        return $technican->setIsDel();
    }

    /**
     * Author:JiangYi
     * Date:2017/5/28
     * Desc:创建技师信息
     * @param Area $area
     * @param string $name
     * @param string $mobile
     * @param string $remark
     * @param string $return
     * @return mixed
     */
    public static function create(Area $area, string $name, string $mobile, string $remark='', $return="throw"){
        $data=[
            'name'=>$name,
            'mobile'=>$mobile,
            'remark'=>$remark,
            'business_area_id'=>$area->id,
        ];
        return Yii::$app->RQ->AR(new BusinessAreaTechnicanAR())->insert($data,$return);
    }
    /**
     * Author:JiangYi
     * Date:2017/5/28
     * Desc:查询技术列表
     * @param int $pageSize
     * @param int $currentPage
     * @param Area|null $area
     * @param array $sortBy
     * @return ActiveDataProvider
     */
    public static function getList(int $pageSize = 10, int $currentPage = 1, Area $area=null,$sortBy=['id'=>SORT_DESC])
    {
		$areaId = $area === null ? null : $area->id;
	    $currentPage = (int)$currentPage or $currentPage = 1;
        $pageSize = (int)$pageSize or $pageSize = 1;
        return new ActiveDataProvider([
            'query' => BusinessAreaTechnicanAR::find()
                ->where(['is_del'=>BusinessAreaTechnicanAR::NO_DEL])
				->andFilterwhere(['business_area_id'=>$areaId])
                ->select(['id'])
                ->asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => $sortBy,
            ],
        ]);
    }



}