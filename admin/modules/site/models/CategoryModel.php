<?php
namespace admin\modules\site\models;

use admin\models\parts\ProductSpuOption;
use common\ActiveRecord\ProductSPUOptionAR;
use common\models\parts\Attribute;
 use Yii;
use common\models\Model;
use common\ActiveRecord\ProductCategoryAR;
use common\models\parts\ProductCategory;
use admin\components\handler\ProductCategoryHandler;
use admin\components\handler\AttributeHandler;

class CategoryModel extends Model
{
    const SCE_MODIFY_CATEGORY = 'modify_category';
    const SCE_REMOVE_CATEGORY = 'remove_category';
    const SCE_ADD_CATEGORY = 'add_category';
    const SCE_GET_CATEGORY_ATTRIBUTES = 'get_category_attributes';
    const SCE_ADD_CATEGORY_ATTRIBUTE = 'add_category_attribute';
    const SCE_DELETE_OPTION = 'delete_option';
    const SCE_EDIT_ATTRIBUTE = 'edit_attribute';
    const SCE_ADD_KEYWORD = 'add_keyword';

    public $category_id;
    public $new_name;
    public $parent_category_id;
    public $name;
    public $end_category_id;
    public $attribute_name;
    public $attribute_options;
    public $attribute_id;
    public $option_id;

    public $keyword;

    public function scenarios()
    {
        return [
            self::SCE_MODIFY_CATEGORY => [
                'category_id',
                'new_name',
            ],
            self::SCE_REMOVE_CATEGORY => [
                'category_id',
            ],
            self::SCE_ADD_CATEGORY => [
                'parent_category_id',
                'name',
            ],
            self::SCE_GET_CATEGORY_ATTRIBUTES => [
                'end_category_id',
            ],
            self::SCE_ADD_CATEGORY_ATTRIBUTE => [
                'end_category_id',
                'attribute_name',
                'attribute_options',
            ],
            self::SCE_DELETE_OPTION => [
                'option_id',
            ],

            self::SCE_EDIT_ATTRIBUTE => [
                'attribute_id',
                'attribute_name',
                'attribute_options',
            ],

            self::SCE_ADD_KEYWORD => [
                'end_category_id',
                'keyword',
            ],
        ];
    }

    public function rules()
    {
        return [
            [
                ['attribute_options','keyword'],
                'default',
                'value' => [],
            ],
            [
                ['parent_category_id'],
                'default',
                'value' => 0,
            ],
            [
                [
                    'category_id',
                    'new_name',
                    'parent_category_id',
                    'end_category_id',
                    'attribute_name',
                    'attribute_options',
                    'keyword',
                ],
                'required',
                'message' => 9001,
            ],
            [
                ['category_id'],
                'exist',
                'targetClass' => ProductCategoryAR::className(),
                'targetAttribute' => 'id',
                'message' => 5031,
            ],
            [
                [
                    'new_name',
                    'name',
                    'attribute_name'
                ],
                'string',
                'length' => [
                    1,
                    20
                ],
                'tooShort' => 5032,
                'tooLong' => 5032,
                'message' => 5032,
            ],
            [
                ['parent_category_id'],
                'common\validators\category\ParentIdValidator',
                'message' => 5051,
            ],
            [
                ['end_category_id'],
                'common\validators\category\EndIdValidator',
                'message' => 5061,
            ],
            [
                ['attribute_options'],
                'each',
                'rule' => [
                    'string',
                    'length' => [
                        1,
                        30
                    ],
                ],
                'allowMessageFromRule' => false,
                'message' => 5071,
            ],
            [
                ['keyword'],
                'each',
                'rule' => [
                    'string',
                    'length' => [
                        1,
                        10
                    ],
                ],
                'allowMessageFromRule' => false,
                'message' => 5371,
            ],
        ];
    }

    public function addCategoryAttribute()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try
        {
            if (!$attribute = AttributeHandler::create($this->attribute_name, $this->attribute_options))
            {
                throw new \Exception('creating attribute failed');
            }
            $productCategory = new ProductCategory(['id' => $this->end_category_id]);
            $productCategory->addAttribute($attribute);
            $transaction->commit();
            return true;
        }
        catch (\Exception $e)
        {
            $transaction->rollBack();
            $this->addError('addCategoryAttribute', 5072);
            return false;
        }
    }

    public function getCategoryAttributes()
    {
        $productCategory = new ProductCategory(['id' => $this->end_category_id]);
        $keyword = $productCategory->getKeyword() ;
        return [
            'attribute'=>$productCategory->attributes->attributesWithOptions,
            'keyword'=>$keyword ? explode(',',$keyword) : '',
        ];
    }

    public function addCategory()
    {
        if (ProductCategoryHandler::create(intval($this->parent_category_id), $this->name, false))
        {
            return true;
        }
        else
        {
            $this->addError('addCategory', 5052);
            return false;
        }
    }

    public function removeCategory()
    {
        $productCategory = new ProductCategory(['id' => $this->category_id]);
        if ($productCategory->setStatus(ProductCategory::STATUS_HIDE, false))
        {
            return true;
        }
        else
        {
            $this->addError('removeCategory', 5041);
            return false;
        }
    }

    public function modifyCategory()
    {
        $productCategory = new ProductCategory(['id' => $this->category_id]);
        if ($productCategory->setTitle($this->new_name, false))
        {
            return true;
        }
        else
        {
            $this->addError('modifyCategory', 5033);
            return false;
        }
    }

    /**
     *====================================================
     * 删除分类选项
     * @return bool
     * @author shuang.li
     * @Date:
     *====================================================
     */
    public function deleteOption()
    {
        $option = new ProductSpuOption(['id' => $this->option_id]);
        if ($option->setDisplay(ProductSPUOptionAR::HIDE))
        {
            return true;
        }
        else
        {
            $this->addError('deleteOption', 5105);
            return false;
        }
    }


    /**
     *====================================================
     * 编辑属性
     * @return bool
     * @author shuang.li
     * @Date:
     *====================================================
     */
    public function editAttribute()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try
        {
            //获取当前属性对象
            $attribute = new Attribute([
                'id' => $this->attribute_id,
            ]);
            //更新当前属性名称
            $attribute->setName($this->attribute_name);

            if (!empty($this->attribute_options))
            {
                $attribute->addOptions($this->attribute_options);
            }
            $transaction->commit();
            return true;
        }
        catch (\Exception $e)
        {
            $transaction->rollBack();
            $this->addError('editAttribute', 5106);
            return false;
        }
    }


    /**
     *====================================================
     * @return bool
     * @author shuang.li
     * 设置分类搜索关键字
     *====================================================
     */
    public function addKeyword(){
        try {
            $keyword = implode(',',$this->keyword);
            $model = new ProductCategory(['id'=>$this->end_category_id]);
            if ($model->setKeyword($keyword) !== false){
                return true;
            }

        }catch (\Exception $exception){
            $this->addError('addKeyword',5370);
            return false;
        }

    }


}
