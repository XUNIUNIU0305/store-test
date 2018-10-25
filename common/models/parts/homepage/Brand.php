<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-11-8
 * Time: 上午10:20
 */

namespace common\models\parts\homepage;


use common\ActiveRecord\HomepageBrandAR;
use common\models\Object;
use common\models\parts\supply\SupplyUser;

class Brand extends Object
{
    public static function queryById($id)
    {
        if($model = HomepageBrandAR::findOne($id)){
            return new static(['ar' => $model]);
        }
        throw new \RuntimeException('无效ID');
    }

    public static function queryItems()
    {
        $res = HomepageBrandAR::find()
            ->orderBy('sort')
            ->all();
        foreach ($res as &$record){
            $record = new static(['ar' => $record]);
        }
        return $res;
    }

    public function init()
    {
        if(!$this->AR){
            $this->AR = new HomepageBrandAR;
        }
    }

    public function getLogoUrl()
    {
        return \Yii::$app->params['OSS_PostHost'] . '/' . $this->logo_name;
    }

    /**
     * @return SupplyUser
     */
    public function getTargetBrand()
    {
        return new SupplyUser(['id' => $this->brand_id]);
    }

    public function setBrand(SupplyUser $user)
    {
        $this->AR->name = $user->getBrandName();
        $this->AR->brand_id = $user->id;
        $this->AR->company_name = $user->getCompanyName();
    }

    public function setLogoName($name)
    {
        $this->AR->logo_name = $name;
    }

    public function update()
    {
        $this->AR->update(false);
    }

    public function insert()
    {
        $this->AR->insert(false);
    }

    public static function autoSort($items)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            foreach ($items as $sort=>$id){
                HomepageBrandAR::updateAll(['sort' => ++$sort], ['id' => $id]);
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e){
            $transaction->rollBack();
            throw $e;
        }
    }
}