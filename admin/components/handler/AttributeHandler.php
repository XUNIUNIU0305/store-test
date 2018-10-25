<?php
namespace admin\components\handler;

use Yii;
use common\components\handler\Handler;
use common\ActiveRecord\ProductSPUAttributeAR;
use common\models\parts\Attribute;

class AttributeHandler extends Handler{

    public static function create(string $attribute, array $options = []){
        if(empty($attribute))return false;
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $attributeId = Yii::$app->RQ->AR(new ProductSPUAttributeAR)->insert([
                'name' => $attribute,
            ]);
            $attributeObj = new Attribute(['id' => $attributeId]);
            if(!empty($options)){
                $attributeObj->addOptions($options);
            }
            $transaction->commit();
            return $attributeObj;
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }
}
