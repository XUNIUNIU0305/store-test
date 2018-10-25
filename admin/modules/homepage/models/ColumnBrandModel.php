<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-31
 * Time: 上午10:13
 */

namespace admin\modules\homepage\models;


use admin\modules\homepage\validators\ColumnValidator;
use common\ActiveRecord\HomepageColumnBrandAR;
use common\components\handler\Handler;
use common\models\Model;
use common\models\parts\homepage\ColumnBrand;
use common\models\parts\supply\SupplyUser;
use common\validators\OSSValidator;

class ColumnBrandModel extends Model
{
    const SCE_LIST = 'get_list';
    const SCE_ADD = 'add';
    const SCE_UPDATE = 'update';
    const SCE_DELETE = 'delete';
    const SCE_GET_BRAND = 'get_brand';
    const MAX_BRAND_COUNT = 6;

    public $column_id;
    public $brand_id;
    public $img;
    public $id;

    public function scenarios()
    {
        return [
            self::SCE_LIST => [
                'column_id'
            ],
            self::SCE_ADD => [
                'column_id',
                'img',
                'brand_id'
            ],
            self::SCE_UPDATE => [
                'id',
                'img',
                'brand_id'
            ],
            self::SCE_DELETE => [
                'id'
            ],
            self::SCE_GET_BRAND => [
                'id'
            ]
        ];
    }

    public function rules()
    {
        return [
            [
                ['column_id'],
                ColumnValidator::class
            ],
            [
                ['img'],
                OSSValidator::class
            ],
            [
                ['brand_id', 'id', 'column_id'],
                'integer',
                'min' => 1,
                'message' => 9002
            ],
            [
                ['img', 'brand_id'],
                'required',
                'on' => [self::SCE_ADD],
                'message' => 9001
            ],
            [
                ['column_id', 'id'],
                'required',
                'message' => 9001
            ]
        ];
    }

    /**
     * 获取品牌列表
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getList()
    {
        $res = ColumnBrand::queryItemsByColumnId($this->column_id);
        return array_map(function($item){
            return Handler::getMultiAttributes($item, [
                'id', 'brand_name', 'brand_id', 'img', 'column_id', 'img_url' => 'imgUrl'
            ]);
        }, $res);
    }

    /**
     * 获取品牌信息
     * @return array
     */
    public function getBrand()
    {
        $brand = ColumnBrand::getInstanceById($this->id);
        return Handler::getMultiAttributes($brand, [
            'id',
            'brand_name',
            'supply_company_name' => 'supplyCompanyName',
            'img',
            'img_url' => 'imgUrl',
            'header_img' => 'headerImg'
        ]);
    }

    /**
     * 添加品牌
     * @return bool
     */
    public function add()
    {
        if(false === $brand = $this->queryBrand()){
            $this->addError('brand_id', 5392);
            return false;
        }

        $model = new HomepageColumnBrandAR;
        $model->setAttributes([
            'img' => $this->img,
            'column_id' => $this->column_id,
            'brand_id' => $this->brand_id,
            'brand_name' => $brand->getBrandName()
        ], false);
        if($model->findAll(["brand_id" => $this->brand_id, 'column_id' => $this->column_id])) {
            $this->addError('brand_id', 5053);
            return false;
        }
        if(current($model->find()->select(['count(*) as count'])->where(['column_id' => $this->column_id])->asArray()->all())['count'] < self::MAX_BRAND_COUNT) {
            $model->insert(false);
            return true;
        } else {
            $this->addError('add', 5411);
            return false;
        }
        return false;
    }

    /**
     * 修改
     * @return bool
     */
    public function update()
    {
        $attributes = $this->getAttributes(['img']);
        if($brand = $this->queryBrand()){
            $attributes['brand_id'] = $this->brand_id;
            $attributes['brand_name'] = $brand->getBrandName();
        }

        if(empty($attributes)) return true;

        try {
            $model = ColumnBrand::getInstanceById($this->brand_id);
            $ar = $model->getAr();
            $ar->setAttributes($attributes, false);
            $ar->update(false);
            return true;
        } catch (\Exception $e){
            $this->addError('id', 5393);
            return false;
        }
    }

    /**
     * 删除
     * @return bool
     */
    public function delete()
    {
        HomepageColumnBrandAR::deleteAll(['id' => $this->id]);
        return true;
    }

    /**
     * @return bool|SupplyUser
     */
    private function queryBrand()
    {
        if(!$this->brand_id) return false;
        try {
            return new SupplyUser(['id' => $this->brand_id]);
        } catch (\Exception $exception){
            return false;
        }
    }
}