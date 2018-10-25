<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/3/8
 * Time: 下午5:27
 */

namespace admin\modules\site\models;


use admin\models\parts\template\CarouselBrandFloor;
use common\ActiveRecord\AdminBrandWallAR;
use common\models\Model;
use common\models\parts\OSSImage;
use common\traits\CheckReturnTrait;

class BrandwallModel extends Model
{
    use CheckReturnTrait;

    const BRAND_SET_BRAND = 'set_brand';
    const BRAND_GET_LIST = 'get_list';
    const BRAND_CONFIRM = 'confirm';


    public $id;
    public $pid;
    public $file_name;
    public $product_url;
    public $sort;
    public $status;
    public $file_suffix;

    public function scenarios()
    {
        return [
            self::BRAND_CONFIRM => ['id'],
            self::BRAND_GET_LIST => [],
            self::BRAND_SET_BRAND => [
                'id',
                'sort',
                'file_name',
                'product_url',
            ],
        ];
    }

    public function rules()
    {
        return [
            [
                [
                    'file_name',
                    'product_url'
                ],
                'required',
                'message' => 5095
            ],
            [
                [
                    'file_name',
                    'product_url',
                    'file_suffix'
                ],
                'string'
            ],
        ];
    }

    /**
     *====================================================
     * 设置品牌墙
     * @return bool
     * @author shuang.li
     * @Date:2017年3月9日
     *====================================================
     */
    public function setBrand()
    {
        $images = new OSSImage([
            'images' => ['filename' => $this->file_name],
        ]);
        $setObj = new CarouselBrandFloor([
            'pid' => $this->id,
            'sort' => $this->sort,
            'type' => 2,
        ]);

        $data = [
            'img_url' => current($images->getPath()),
            'file_name' => $this->file_name,
            'product_url' => $this->product_url,
        ];


        if ($setObj->multipleUpdate($data))
        {
            return true;
        }

        $this->addError('setBarnd', 5094);
        return false;

    }

    /**
     *====================================================
     * 确认使用该模板
     * @return bool
     * @author shuang.li
     * @Date:2017年3月9日
     *====================================================
     */
    public function confirm()
    {
        $setObj = new CarouselBrandFloor([
            'id' => $this->id,
            'type' => 2
        ]);

        if ($setObj->setStatus())
        {
            return true;
        }

        $this->addError('confirm', 5096);
        return false;
    }

    /**
     *====================================================
     * 初始化 页面标志位
     * @return array|\yii\db\ActiveRecord[]
     * @author shuang.li
     * @Date:2017年3月8日
     *====================================================
     */
    public function getList()
    {

        $brandWallData = \Yii::$app->RQ->AR(new AdminBrandWallAR())->all([
            'select' => [
                'id',
                'pid',
                'file_name',
                'img_url',
                'product_url',
                'status',
                'sort'
            ],
        ]);

        return array_values(self::returnTree($brandWallData));
    }


}