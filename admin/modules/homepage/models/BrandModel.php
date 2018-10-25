<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-11-8
 * Time: 上午10:10
 */

namespace admin\modules\homepage\models;

use common\ActiveRecord\HomepageBrandAR;
use common\components\handler\Handler;
use common\models\Model;
use common\models\parts\homepage\Brand;
use common\models\parts\supply\SupplyUser;
use common\validators\OSSValidator;

class BrandModel extends Model
{
    const SCE_GET_LIST = 'get_list';
    const SCE_GET_BRAND = 'get_brand';
    const SCE_UPDATE = 'update';
    const SCE_CREATE = 'create';
    const SCE_DELETE = 'delete';
    const SCE_SORT = 'sort';

    public $id;
    public $brand_id;
    public $logo_name;
    public $sort_items;

    public function scenarios()
    {
        return [
            self::SCE_GET_LIST  => [],
            self::SCE_GET_BRAND => [
                'id'
            ],
            self::SCE_UPDATE    => [
                'id',
                'brand_id',
                'logo_name'
            ],
            self::SCE_CREATE    => [
                'brand_id',
                'logo_name'
            ],
            self::SCE_DELETE    => [
                'id'
            ],
            self::SCE_SORT      => [
                'sort_items'
            ]
        ];
    }

    public function rules()
    {
        return [
            [
                ['id', 'brand_id', 'logo_name', 'sort_items'],
                'required',
                'message' => 9001
            ],
            [
                ['id', 'brand_id'],
                'integer',
                'min'     => 1,
                'message' => 9002
            ],
            [
                ['logo_name'],
                'string',
                'message' => 9002
            ],
            [
                ['logo_name'],
                OSSValidator::class
            ]
        ];
    }

    /**
     * 获取品牌列表
     * @return array
     */
    public function getList()
    {
        return array_map(function ($item) {
            return Handler::getMultiAttributes($item, [
                'id',
                'brand_id',
                'name',
                'logo_name',
                'logo_url' => 'logoUrl'
            ]);
        }, Brand::queryItems());
    }

    /**
     * 获取品牌详情
     * @return array|bool
     */
    public function getBrand()
    {
        try {
            $brand       = Brand::queryById($this->id);
            $targetBrand = $brand->getTargetBrand();
            return [
                'id'          => $brand->id,
                'brand_id'    => $brand->brand_id,
                'logo_name'   => $brand->logo_name,
                'logo_url'    => $brand->getLogoUrl(),
                'brand_name'  => $targetBrand->getBrandName(),
                'supply_name' => $targetBrand->getCompanyName(),
                'logo_origin' => $targetBrand->getHeaderImg()
            ];
        } catch (\Exception $e) {
            $this->addError('get_brand', 9002);
            return false;
        }
    }

    /**
     * 修改
     * @return bool
     */
    public function update()
    {
        try {
            $brand  = Brand::queryById($this->id);
            $supply = new SupplyUser(['id' => $this->brand_id]);
            $brand->setBrand($supply);
            $brand->setLogoName($this->logo_name);
            $brand->update();
            return true;
        } catch (\Exception $e) {
            $this->addError('get_brand', 9002);
            return false;
        }
    }

    /**
     * 添加
     * @return bool
     */
    public function create()
    {
        try {
            $supply = new SupplyUser(['id' => $this->brand_id]);
            $brand  = new Brand;
            $brand->setBrand($supply);
            $brand->setLogoName($this->logo_name);
            $brand->insert();
            return true;
        } catch (\Exception $e) {
            $this->addError('get_brand', 9002);
            return false;
        }
    }

    /**
     * 删除
     * @return bool
     */
    public function delete()
    {
        HomepageBrandAR::deleteAll(['id' => $this->id]);
        return true;
    }

    public function sort()
    {
        try {
            Brand::autoSort($this->sort_items);
            return true;
        } catch (\Exception $e) {
            $this->addError('get_brand', 9002);
            return false;
        }
    }
}