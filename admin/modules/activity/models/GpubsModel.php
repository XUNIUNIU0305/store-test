<?php
/**
 * Created by PhpStorm.
 * User: forrestgao
 * Date: 18-6-21
 * Time: 下午9:09
 */

namespace admin\modules\activity\models;

use admin\components\handler\GpubsHandler;
use common\ActiveRecord\ActivityGpubsProductAR;
use common\ActiveRecord\ProductAR;
use common\ActiveRecord\ActivityGpubsProductSkuAR;
use common\models\Model;
use common\models\parts\gpubs\GpubsProduct;
use common\models\parts\Product;
use common\models\parts\ProductSKU;
use common\models\RapidQuery;
use supply\models\ReleaseModel;
use Yii;

class GpubsModel extends Model
{
    const SCE_CREATE_GPUBS = 'create_gpubs';
    const SCE_CREATE_GPUBS_DELIVER = 'create_gpubs_deliver';
    const SCE_GET_GPUBS_DETAIL = 'get_gpubs_detail';
    const SCE_UPDATE_GPUBS = 'update_gpubs';
    const SCE_GET_GPUBS_LIST = 'get_gpubs_list';
    const SCE_SEARCH = 'search';
    const SCE_SEARCH_GPUBS = 'search_gpubs';
    const SCE_SET_STATUS = 'set_status';
    const SCE_SET_RECOMMENT = 'set_recomment';

    const ONE_HOUR_SECONDS = 3600;
    const GPUBS_TYPE_ALL    = 3;#搜索全部

    public $product_id;             // 商品id
    public $attrs;
    public $cartesian;
    public $sku;

    public $max_launch_per_user;    // 每用户最大可开团数（账户开团上线）
    public $min_quantity_per_group; // 每团成立的最小商品数量（拼购人数）
    public $lifecycle_per_group;    // 每团等待成立时间（拼购时间）
    public $start_datetime;         // 活动开始时间
    public $end_datetime;           // 活动结束时间

    public $gpubs_type;             // 拼团类型
    public $gpubs_rule_type;        // 成团规则类型
    public $min_member_per_group;   // 每团成立的最小人数
    public $min_quanlity_per_member_of_group; // 每团每人购买的最小数量
    public $description;
    public $share_title;
    public $share_subtitle;
    public $filename;

    public $gpubsProductId;

    public $current_page;
    public $page_size;
    public $status;

    public $search_condition;       // 拼购商品查找条件
    public $search_gpubs_type;      // 拼购商品查找拼购类型

    public $is_hot; // 1-否 2-是
    public function scenarios()
    {
        return [
            self::SCE_CREATE_GPUBS => [
                'lifecycle_per_group',
                'start_datetime',
                'end_datetime',
                'max_launch_per_user',
                'min_quantity_per_group',
                'product_id',
                'sku',
                'description',
                'share_title',
                'share_subtitle',
                'filename',
            ],
            self::SCE_CREATE_GPUBS_DELIVER => [
                'lifecycle_per_group',
                'start_datetime',
                'end_datetime',
                'max_launch_per_user',
                'min_quantity_per_group',
                'product_id',
                'sku',
                'description',
                'share_title',
                'share_subtitle',
                'filename',
                'gpubs_type',
                'gpubs_rule_type',
                'min_member_per_group',
                'min_quanlity_per_member_of_group',
            ],
            self::SCE_GET_GPUBS_DETAIL => [
                'gpubsProductId',
            ],
            self::SCE_UPDATE_GPUBS => [
                'gpubsProductId',
                'sku',
                'share_title',
                'share_subtitle',
                'filename',
            ],
            self::SCE_GET_GPUBS_LIST => [
                'current_page',
                'page_size',
            ],
            self::SCE_SEARCH => [
                'product_id',
            ],
            self::SCE_SET_STATUS => [
                'product_id',
                'status',
            ],
            self::SCE_SEARCH_GPUBS => [
                'search_condition',
                'search_gpubs_type',
                'current_page',
                'page_size',
            ],
            self::SCE_SET_RECOMMENT => [
                'is_hot',
                'product_id',
            ],
        ];
    }

    public function rules()
    {
        return [
            [
                [
                    'product_id', 'lifecycle_per_group', 'start_datetime', 'end_datetime',
                    'max_launch_per_user', 'gpubsProductId',
                    'attrs', 'cartesian', 'sku', 'current_page','page_size',
                    'description', 'share_title', 'share_subtitle', 'filename', 'search_gpubs_type',
                    'is_hot',
                ],
                'required',
                'message' => 9001,
            ],
            [
                ['min_quantity_per_group'],
                'required',
                'message' => 9001,
                'on' => self::SCE_CREATE_GPUBS,
            ],
            [
                ['gpubs_type', 'gpubs_rule_type'],
                'required',
                'message' => 9001,
                'on' => self::SCE_CREATE_GPUBS_DELIVER,
            ],
            [
                ['gpubs_type'],
                'in',
                'range' => [
                    GpubsProduct::GPUBS_TYPE_INVITE,
                    GpubsProduct::GPUBS_TYPE_DELIVER
                ],
                'message' => 5528,
                'on' => self::SCE_CREATE_GPUBS_DELIVER,
            ],
            [
                ['gpubs_rule_type'],
                'in',
                'range' => [
                    GpubsProduct::STATUS_GPUBS_RULE_MEMBER,
                    GpubsProduct::STATUS_GPUBS_RULE_NUMBER,
                    GpubsProduct::STATUS_GPUBS_PRE_NUMBER
                ],
                'message' => 5529,
                'on' => self::SCE_CREATE_GPUBS_DELIVER,
            ],
            [
                ['product_id'],
                'exist',
                'targetClass' => ProductAR::className(),
                'targetAttribute' => ['product_id' => 'id'],
                'filter' => ['sale_status' => Product::SALE_STATUS_ONSALE],
                'message' => 5500,
            ],
            [
                ['start_datetime', 'end_datetime'],
                'date',
                'format' => 'yyyy-M-d H:m:s',
                'message' => 5504,
            ],
            [
                ['lifecycle_per_group'],
                'integer',
                'min' => 0,
                'message' => 5535,
            ],
            [
                ['max_launch_per_user'],
                'integer',
                'min' => 1,
                'tooSmall' => 5506,
                'message' => 5506,
            ],
            [
                ['min_quantity_per_group'],
                'integer',
                'min' => 2,
                'tooSmall' => 5505,
                'message' => 5505,
            ],
            [
                ['min_member_per_group'],
                'integer',
                'min' => 2,
                'max' => 99,
                'tooSmall' => 5522,
                'tooBig' => 5522,
                'message' => 5522,
                'on' => self::SCE_CREATE_GPUBS_DELIVER,
            ],
            [
                ['min_quanlity_per_member_of_group'],
                'integer',
                'min' => 2,
                'tooSmall' => 5524,
                'message' => 5524,
                'on' => self::SCE_CREATE_GPUBS_DELIVER,
            ],
            [
                ['cartesian'],
                'common\validators\gpubs\CartesianValidator',
                'attrs' => $this->attrs,
                'productId' => $this->product_id,
                'contain' => [
                    'product_sku_id' => [
                        'class' => 'yii\validators\NumberValidator',
                        'min' => 0,
                    ],
                    'original_price' => [
                        'class' => 'yii\validators\NumberValidator',
                        'min' => 0,
                    ],
                    'price' => [
                        'class' => 'yii\validators\NumberValidator',
                        'min' => 0,
                    ],
                    'stock' => [
                        'class' => 'yii\validators\NumberValidator',
                        'min' => 0,
                    ],
                ],
                'validateSkuRule' => true,
                'message' => 5502,
            ],
            [
                ['gpubsProductId'],
                'exist',
                'targetClass' => ActivityGpubsProductAR::className(),
                'targetAttribute' => ['gpubsProductId' => 'id'],
                'message' => 5507,
            ],
            [
                ['sku'],
                'common\validators\gpubs\SkuValidator',
                'productId' => $this->product_id,
                'minQuantityPerGroup' => $this->min_quantity_per_group,
                'canContain' => [
                    'original_price' => [
                        'class' => 'yii\validators\NumberValidator',
                        'min' => 0,
                    ],
                    'price' => [
                        'class' => 'yii\validators\NumberValidator',
                        'min' => 0.01,
                    ],
                    'stock' => [
                        'class' => 'yii\validators\NumberValidator',
                        'min' => 0,
                    ],
                ],
                'message' => 5521,
            ],
            [['current_page'], 'default', 'value' => 1,],
            [['page_size'], 'default', 'value' => 10,],
            [
                ['status'],
                'in',
                'range' => [GpubsProduct::STATUS_CLOSED, GpubsProduct::STATUS_ACTIVE],
                'message' => 9002,
            ],
            [
                ['description'],
                'string',
                'length' => [1, 50],
                'tooLong' => 5525,
                'tooShort' => 5525,
                'message' => 5525,
            ],
            [
                ['share_title'],
                'string',
                'length' => [1, 25],
                'tooLong' => 5526,
                'tooShort' => 5526,
                'message' => 5526,
            ],
            [
                ['share_subtitle'],
                'string',
                'length' => [1, 50],
                'tooLong' => 5527,
                'tooShort' => 5527,
                'message' => 5527,
            ],
            [
                ['search_condition'],
                'string',
                'length' => [0, 255],
                'tooLong' => 5530,
                'tooShort' => 5530,
                'message' => 5530,
            ],
            [
                ['search_gpubs_type'],
                'in',
                'range' => [
                        GpubsProduct::GPUBS_TYPE_INVITE,
                        GpubsProduct::GPUBS_TYPE_DELIVER,
                        self::GPUBS_TYPE_ALL,
                    ],
                'message' => 5528,
            ],
            [
                ['is_hot'],
                'in',
                'range' => [
                    GpubsProduct::HOT_RECOMMENT_NOT,
                    GpubsProduct::HOT_RECOMMENT_IS,
                ],
                'message' => 5531,
            ],
        ];
    }

    public function createGpubs()
    {
        if (strtotime($this->start_datetime) > strtotime($this->end_datetime)) {
            $this->addError('createGpubs', 5504);
            return false;
        }
        if (ActivityGpubsProductAR::find()->where(['product_id' => $this->product_id])->count() >= 1) {
            $this->addError('createGpubs', 5517);
            return false;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $gpubsProduct = GpubsHandler::create($this->product_id, $this->max_launch_per_user, $this->lifecycle_per_group, $this->start_datetime, $this->end_datetime, $this->min_quantity_per_group, $this->description, $this->share_title, $this->share_subtitle, $this->filename);
            if (!$gpubsProduct) throw new \Exception;
            $gpubsProduct->setGpubsProductSKU($this->product_id, $this->sku);
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            $this->addError('createGpubs', 5503);
            return false;
        }
    }

    public function createGpubsDeliver()
    {
        if (strtotime($this->start_datetime) > strtotime($this->end_datetime)) {
            $this->addError('createGpubsDeliver', 5504);
            return false;
        }
        if (ActivityGpubsProductAR::find()->where(['product_id' => $this->product_id])->count() >= 1) {
            $this->addError('createGpubsDeliver', 5517);
            return false;
        }
        if (ProductAR::find()->where(['id' => $this->product_id])->select(['customer_limit'])->scalar() == Product::TYPE_SUPPLY){
            $this->addError('getGpubsList', 5534);
            return false;
        }
        try {
            switch (intval($this->gpubs_rule_type)) {
                case GpubsProduct::STATUS_GPUBS_RULE_MEMBER :
                    if (empty($this->min_member_per_group)) {
                        throw new \Exception();
                    }
                    break;
                case GpubsProduct::STATUS_GPUBS_RULE_NUMBER :
                    if (empty($this->min_quantity_per_group)) {
                        throw new \Exception();
                    }
                    break;
                case GpubsProduct::STATUS_GPUBS_PRE_NUMBER :
                    if (empty($this->min_member_per_group) || empty($this->min_quanlity_per_member_of_group)) {
                        throw new \Exception();
                    }
                    break;
                default:
                    throw new \Exception();
            }
        } catch (\Exception $e) {
            $this->addError('createGpubsDeliver', 5529);
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $gpubsProduct = GpubsHandler::createDeliver($this->product_id, $this->max_launch_per_user, $this->lifecycle_per_group, $this->start_datetime, $this->end_datetime, $this->min_quantity_per_group,
                $this->description, $this->share_title, $this->share_subtitle, $this->filename, intval($this->gpubs_type), intval($this->gpubs_rule_type), $this->min_member_per_group, $this->min_quanlity_per_member_of_group);
            if (!$gpubsProduct) throw new \Exception;
            $gpubsProduct->setGpubsProductSKU($this->product_id, $this->sku);
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            $this->addError('createGpubsDeliver', 5503);
            return false;
        }
    }

    public function getGpubsDetail()
    {
        try {
            $productId = ActivityGpubsProductAR::find()->select(['product_id'])->where(['id' => $this->gpubsProductId])->scalar();
            $productImage = (new Product(['id' => $productId]))->bigImages->mainImage->path;
            $productSKU = new ProductSKU(['productId' => $productId]);
            $product = ProductAR::find()->select(['title', 'product_category_id'])->where(['id' => $productId])->one();
            $fullCategory = (new ReleaseModel(['category_id' => $product['product_category_id']]))->fullCategory;

            $gpubs = (new RapidQuery(new ActivityGpubsProductAR))->one([
                'select' => ['id', 'max_launch_per_user', 'lifecycle_per_group', 'activity_start_datetime', 'activity_end_datetime', 'gpubs_type', 'gpubs_rule_type',
                'description', 'share_title', 'share_subtitle', 'filename', 'min_member_per_group', 'min_quantity_per_group', 'min_quantity_per_member_of_group'],
                'where' => ['product_id' => $productId],
            ]);
            switch ($gpubs['gpubs_rule_type']) {
                case GpubsProduct::STATUS_GPUBS_RULE_MEMBER :
                    unset($gpubs['min_quantity_per_group']);
                    unset($gpubs['min_quanlity_per_member_of_group']);
                    break;
                case GpubsProduct::STATUS_GPUBS_RULE_NUMBER :
                    unset($gpubs['min_member_per_group']);
                    unset($gpubs['min_quanlity_per_member_of_group']);
                    break;
                case GpubsProduct::STATUS_GPUBS_PRE_NUMBER :
                    unset($gpubs['min_quantity_per_group']);
                    break;
                default:
                    break;
            }
            $gpubs['filename'] = Yii::$app->params['OSS_PostHost'] . '/' . $gpubs['filename'];
            $gpubs['lifecycle_per_group'] /= self::ONE_HOUR_SECONDS;
        } catch (\Exception $e) {
            $this->addError('getGpubsDetail', 5520);
            return false;
        }
        return [
            'title' => $product->title,
            'image_path' => $productImage,
            'full_category' => $fullCategory,
            'gpubs' => $gpubs,
            'attributes' => $productSKU->getAttributeWithOption(),
            'sku' => GpubsHandler::getSkuDetail($productId),
        ];
    }

    public function updateGpubs()
    {
        if (ActivityGpubsProductAR::find()->where(['id' => $this->gpubsProductId, 'status' => GpubsProduct::STATUS_ACTIVE])->exists()) {
            $this->addError('updateGpubs', 5508);
            return false;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!(new GpubsProduct(['id' => $this->gpubsProductId]))->updateGpubsProductSKU(
                $this->sku, $this->share_title, $this->share_subtitle, $this->filename
            )) {
                throw new \Exception;
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            $this->addError('updateGpubs', 5508);
            return false;
        }
    }

    public function getGpubsList()
    {
        if ($list = GpubsHandler::getProducts($this->current_page, $this->page_size)) {
            return $list;
        } else {
            $this->addError('getGpubsList', 5511);
            return false;
        }
    }

    public function search()
    {
        $categoryId = ProductAR::find()->select(['product_category_id'])->where(['id' => $this->product_id])->scalar();
        if (ProductAR::find()->select(['customer_limit'])->where(['id' => $this->product_id])->scalar() != Product::TYPE_REPUBLICS){
            $this->addError('getGpubsList', 5534);
            return false;
        }

        if (ProductAR::find()->select(['customization'])->where(['id' => $this->product_id])->scalar() == Product::TYPE_CUSTOMIZATION){
            $this->addError('getGpubsList',5536);
            return false;
        }

        if ($product = GpubsHandler::getProduct($this->product_id)) {
            $productSKU = new ProductSKU(['productId' => $this->product_id]);
            return [
                'full_category' => (new ReleaseModel(['category_id' => $categoryId]))->fullCategory,
                'product' => $product,
                'attributes' => $productSKU->getAttributeWithOption(),
                'sku' => $productSKU->getSKU(),
            ];
        } else {
            $this->addError('getGpubsList', 5512);
            return false;
        }
    }

    public function searchGpubs()
    {
        if (!isset($this->search_condition)) {
            $this->search_condition = '';
        }
        if ($gpubsProduct = GpubsHandler::getGpubsProduct($this->search_gpubs_type, $this->search_condition,$this->current_page,$this->page_size)) {
            return $gpubsProduct;
        } else {
            $this->addError('getGpubs', 5519);
            return false;
        }
    }

    public function setStatus()
    {
        if ($gpubsProduct = ActivityGpubsProductAR::find()
            ->where(['product_id' => $this->product_id])
           ->one())
        {
            if (ActivityGpubsProductSkuAR::find()->where(['product_id' => $this->product_id])->sum('stock') == 0 and $this->status == 1){
                $this->addError('setStatus', 10004);
                return false;
            }

            if (ActivityGpubsProductAR::find()->where(['product_id' => $this->product_id, 'status' => $this->status])->exists()) {
                return true;
            }
            $gpubsProduct->status = $this->status;
            if ($gpubsProduct->save()) {
                return true;
            }
        } else {
            $this->addError('setStatus', 5513);
            return false;
        }
    }

    public function setRecomment()
    {
        if ($gpubsProduct = ActivityGpubsProductAR::find()->where(['product_id' => $this->product_id])->one()) {
            $gpubsProduct->hot_recommend = $this->is_hot;
            if ($gpubsProduct->save()) {
                return true;
            }
        }
        $this->addError('setRecomment', 5531);
        return false;
    }
}
