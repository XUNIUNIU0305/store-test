<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-30
 * Time: 下午3:52
 */

namespace admin\modules\homepage\models;


use admin\modules\homepage\validators\ColumnValidator;
use common\ActiveRecord\HomepageColumnItemAR;
use common\components\handler\Handler;
use common\models\Model;
use common\models\parts\homepage\ColumnItem;
use common\models\parts\ProductCategory;
use common\validators\OSSValidator;

class ColumnItemModel extends Model
{
    const SCE_LIST      = 'get_list';
    const SCE_ADD       = 'add';
    const SCE_UPDATE    = 'update';
    const SCE_DELETE    = 'delete';
    const SCE_BIND      = 'bind';
    const SCE_UNBIND    = 'unbind';
    const MAX_ITEMS_COUNT = 30;

    public $column_id;
    public $name;
    public $img;
    public $id;
    public $cate_id;

    public function scenarios()
    {
        return [
            self::SCE_LIST   => [
                'column_id'
            ],
            self::SCE_ADD    => [
                'column_id',
                'name',
                'img'
            ],
            self::SCE_UPDATE => [
                'id',
                'name',
                'img'
            ],
            self::SCE_DELETE => [
                'id'
            ],
            self::SCE_BIND => [
                'id',
                'cate_id'
            ],
            self::SCE_UNBIND => [
                'id'
            ]
        ];
    }

    public function rules()
    {
        return [
            [
                ['column_id', 'id', 'cate_id'],
                'required',
                'message' => 9001
            ],
            [
                ['img', 'name'],
                'required',
                'on'      => [self::SCE_ADD],
                'message' => 9001
            ],
            [
                ['column_id', 'id', 'cate_id'],
                'integer',
                'min'     => 1,
                'tooSmall' => 9002,
                'message' => 9002
            ],
            [
                ['name', 'img'],
                'string',
                'message' => 9002
            ],
            [
                ['img'],
                OSSValidator::class
            ],
            [
                ['column_id'],
                ColumnValidator::class
            ]
        ];
    }

    /**
     * 获取列表
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getList()
    {
        $res = ColumnItem::queryItemsByColumnId($this->column_id);
        return array_map(function($item){
            return Handler::getMultiAttributes($item,[
                'id', 'name', 'cate_id', 'img', 'img_url' => 'imgUrl'
            ]);
        }, $res);
    }

    /**
     * 添加分类
     * @return bool
     */
    public function add()
    {
        $item = new HomepageColumnItemAR;
        $item->setAttributes($this->getAttributes(['column_id', 'name', 'img']), false);
        if(current($item->find()->select(['count(*) as count'])
                ->where(['column_id' => $this->column_id])->asArray()->all())['count'] >= self::MAX_ITEMS_COUNT) {
            $this->addError('add', 5412);
            return false;
        }
        $item->insert(false);
        return true;
    }

    /**
     * 修改分类
     * @return bool
     */
    public function update()
    {
        $attributes = $this->getAttributes(['name', 'img']);
        if (empty($attributes)) return true;
        try {
            $item = ColumnItem::getInstanceById($this->id);
            $ar   = $item->getAr();
            $ar->setAttributes($attributes, false);
            $ar->update(false);
            return true;
        } catch (\Exception $e) {
            $this->addError('id', 5394);
            return false;
        }
    }

    /**
     * 删除分类
     * @return bool
     */
    public function delete()
    {
        HomepageColumnItemAR::deleteAll(['id' => $this->id]);
        return true;
    }

    /**
     * 绑定分类
     * @return bool
     */
    public function bind()
    {
        if(null === $cate = $this->queryCate()){
            $this->addError('cate_id', 5396);
            return false;
        }

        try {
            $item = ColumnItem::getInstanceById($this->id);
            $ar = $item->getAr();
            $ar->cate_id = $this->cate_id;
            $ar->update(false);
            return true;
        } catch (\Exception $e){
            $this->addError('id', 5394);
            return false;
        }
    }

    /**
     * 解绑分类
     * @return bool
     */
    public function unbind()
    {
        try {
            $item = ColumnItem::getInstanceById($this->id);
            $ar = $item->getAr();
            $ar->cate_id = 0;
            $ar->update(false);
            return true;
        } catch (\Exception $e){
            $this->addError('id', 5394);
            return false;
        }
    }

    /**
     * @return ProductCategory|null
     */
    private function queryCate()
    {
        try {
            return new ProductCategory(['id' => $this->cate_id]);
        } catch (\Exception $e){
            return null;
        }
    }
}