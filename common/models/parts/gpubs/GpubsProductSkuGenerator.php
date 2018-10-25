<?php
/**
 * Created by PhpStorm.
 * User: forrestgao
 * Date: 18-6-22
 * Time: 上午4:35
 */

namespace common\models\parts\gpubs;

use common\ActiveRecord\ActivityGpubsProductSkuAR;
use yii\base\InvalidConfigException;
use yii\base\Object;
use Yii;

class GpubsProductSkuGenerator extends Object
{
    public $productId;
    public $gpubsProductId;
    public $items;

    public function init()
    {
        if (empty($this->productId) || empty($this->gpubsProductId) || empty($this->items) || !is_array($this->items)) {
            throw new InvalidConfigException();
        }
    }

    public function generate()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($this->items as $item) {
                Yii::$app->RQ->AR(new ActivityGpubsProductSkuAR())->insert([
                    'activity_gpubs_product_id' => $this->gpubsProductId,
                    'product_id' => $this->productId,
                    'product_sku_id' => $item['product_sku_id'],
                    'price' => $item['price'],
                    'stock' => $item['stock'],
                ]);
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }
}