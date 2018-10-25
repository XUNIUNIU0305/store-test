<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/3/15
 * Time: 下午2:19
 */

namespace admin\models\parts\template;


use common\ActiveRecord\AdminBrandWallAR;
use common\ActiveRecord\AdminFloorAR;
use common\ActiveRecord\AdminFloorGoodsAR;
use common\ActiveRecord\AdminImageCarouselAR;
use yii\base\Object;

use Yii;

class CarouselBrandFloor extends Object
{


    const TYPE_CAROUSEL = 1;
    const TYPE_BRAND = 2;
    const TYPE_FLOOR = 3;
    const TYPE_GOODS = 4;


    public $id;
    protected $AR;
    public $type;
    public $data;
    public $pid;
    public $sort;


    public function init()
    {
        if ($this->type)
        {
            switch ($this->type)
            {
            case self::TYPE_CAROUSEL:
                $this->AR = AdminImageCarouselAR::findOne($this->id);
                break;

            case self::TYPE_BRAND:
                if ($this->pid)
                {
                    $this->AR = AdminBrandWallAR::findOne([
                        'pid' => $this->pid,
                        'sort' => $this->sort,
                    ]);
                }
                else
                {
                    $this->AR = AdminBrandWallAR::findOne($this->id);
                }

                break;

            case self::TYPE_FLOOR:
                $this->AR = AdminFloorAR::findOne($this->id);
                break;

            case self::TYPE_GOODS:
                $this->AR = AdminFloorGoodsAR::findOne($this->id);
                break;
            }
        }
    }

    //批量更新
    public function multipleUpdate($data)
    {
        if ($this->AR){
            return Yii::$app->RQ->AR($this->AR)->update($data);
        }
        return false;
    }

    //获取图片名称
    public function getFileName()
    {
        if ($this->AR)
        {
            return $this->AR->file_name ?? false;
        }
        return $this->data['file_name'] ?? false;
    }

    //获取轮播图 图片地址
    public function getImgUrl()
    {
        if ($this->AR)
        {
            return $this->AR->img_url ?? false;
        }
        return $this->data['img_url'] ?? false;
    }

    //获取排序
    public function getSort()
    {
        if ($this->AR)
        {
            return $this->AR->sort ?? false;
        }
        return $this->data['sort'] ?? false;
    }

    //获取轮播图对应商品链接
    public function getProductUrl()
    {
        if ($this->AR)
        {
            return $this->AR->product_url;
        }
        return $this->data['product_url'] ?? false;
    }

    //获取楼层标题简写
    public function getTitleSimple()
    {
        if ($this->AR)
        {
            return $this->AR->title_simple ?? false;
        }
        return $this->data['title_simple'] ?? false;
    }

    //获取楼层图片地址
    public function getFloorUrl()
    {
        if ($this->AR)
        {
            return $this->AR->floor_url ?? false;
        }
        return $this->data['floor_url'] ?? false;
    }

    //获取楼层中文标题
    public function getTitleCh()
    {
        if ($this->AR)
        {
            return $this->AR->title_ch ?? false;
        }
        return $this->data['title_ch'] ?? false;
    }

    //获取楼层英文标题
    public function getTitleEn()
    {
        if ($this->AR)
        {
            return $this->AR->title_en ?? false;
        }
        return $this->data['title_en'] ?? false;
    }

    //获取楼层颜色
    public function getFloorColor()
    {
        if ($this->AR)
        {
            return $this->AR->floor_color ?? false;
        }
        return $this->data['floor_color'] ?? false;
    }

    //获取商品所属楼层id
    public function getFid()
    {
        if ($this->AR)
        {
            return $this->AR->fid ?? false;
        }
        return $this->data['fid'] ?? false;
    }

    //获取商品名称
    public function getGoodName()
    {
        if ($this->AR)
        {
            return $this->AR->good_name ?? false;
        }
        return $this->data['good_name'] ?? false;
    }

    //获取商品卖点1
    public function getSaleOne()
    {
        if ($this->AR)
        {
            return $this->AR->sale_one ?? false;
        }
        return $this->data['sale_one'] ?? false;
    }

    //获取商品卖点2
    public function getSaleTwo()
    {
        if ($this->AR)
        {
            return $this->AR->sale_two ?? false;
        }
        return $this->data['sale_two'] ?? false;
    }

    //获取商品id
    public function getGoodId()
    {
        if ($this->AR)
        {
            return $this->AR->good_id ?? false;
        }
        return $this->data['good_id'] ?? false;
    }

    //获取商品图片地址
    public function getGoodUrl()
    {
        if ($this->AR)
        {
            return $this->AR->good_url ?? false;
        }
        return $this->data['good_url'] ?? false;
    }


    //品牌馆设置默认选中
    public function setStatus()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try
        {
            if (AdminBrandWallAR::updateAll([
                    'status' => AdminBrandWallAR::NORMAL_STATUS,
                ], 'id > 0') !== false
            )
            {
                if (AdminBrandWallAR::updateAll(['status' => AdminBrandWallAR::DEFAULT_STATUS], 'id = ' . $this->id) == false)
                {
                    throw new \Exception();
                }
                $transaction->commit();
                return true;

            }
        }
        catch (\Exception $e)
        {
            $transaction->rollBack();
            return false;
        }
    }

    //
    public function updateCarousel($data)
    {
        $exists = Yii::$app->RQ->AR(new AdminImageCarouselAR())->exists([
            'where' => "id <> {$this->AR->id} and sort = {$data['sort']} ",
            'limit' => 1,
        ]);

        if (!$exists)
        {
            return $this->multipleUpdate($data);
        }

        return false;
    }


    //商品楼层更新
    public function updateFloor($data)
    {

        $exists = Yii::$app->RQ->AR(new AdminFloorAR)->exists([
            'where' => "id <> {$this->AR->id} and sort = {$data['sort']} ",
            'limit' => 1,
        ]);

        if (!$exists)
        {
            return $this->multipleUpdate($data);
        }

        return false;
    }


    //更新楼层商品
    public function updateFloorGood($data)
    {

        $exist = Yii::$app->RQ->AR(new AdminFloorGoodsAR())->exists([
            'where' => "id <> {$this->AR->id} and (sort = {$data['sort']} or good_id = {$data['good_id']} )and fid = {$this->AR->fid} ",
            'limit' => 1,
        ]);

        if (!$exist)
        {
            return $this->multipleUpdate($data);
        }

        return false;
    }
}