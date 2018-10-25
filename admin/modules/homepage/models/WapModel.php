<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-11-6
 * Time: 上午9:57
 */

namespace admin\modules\homepage\models;


use common\ActiveRecord\HomepageWapAR;
use common\models\Model;
use common\validators\OSSValidator;

class WapModel extends Model
{
    const SCE_GET_LIST = 'get_list';
    const SCE_UPDATE = 'update';
    const SCE_CREATE = 'create';
    const SCE_DELETE = 'delete';
    const SCE_SORT = 'sort';

    public $file_name;
    public $product_url;
    public $sort = 99;
    public $id;
    public $sort_items;

    public function scenarios()
    {
        return [
            self::SCE_GET_LIST => [],
            self::SCE_UPDATE   => [
                'file_name',
                'product_url',
                'sort',
                'id'
            ],
            self::SCE_CREATE   => [
                'file_name',
                'product_url',
                'sort'
            ],
            self::SCE_DELETE   => [
                'id'
            ],
            self::SCE_SORT => [
                'sort_items'
            ]
        ];
    }

    public function rules()
    {
        return [
            [
                ['sort'],
                'integer',
                'min'     => 1,
                'max'     => 99,
                'message' => 9002
            ],
            [
                ['id'],
                'integer',
                'min'     => 1,
                'message' => 9002
            ],
            [
                ['file_name', 'product_url', 'id', 'sort_items'],
                'required',
                'message' => 9001
            ],
            [
                ['file_name'],
                OSSValidator::class
            ],
            [
                ['product_url'],
                'url',
                'message' => 9002
            ]
        ];
    }

    /**
     * 获取轮播列表
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getList()
    {
        return HomepageWapAR::find()
            ->select(['id', 'img_url', 'file_name', 'product_url', 'sort'])
            ->where(['is_del' => HomepageWapAR::IS_DEL_NO])
            ->orderBy('sort asc')
            ->asArray()->all();
    }

    /**
     * 修改轮播
     * @return bool
     */
    public function update()
    {
        if (!$model = HomepageWapAR::findOne($this->id)) {
            $this->addError('id', 5397);
            return false;
        }
        $model->setAttributes($this->getAttributes(['file_name', 'product_url', 'sort']), false);
        if ($this->file_name) {
            $model->img_url = \Yii::$app->params['OSS_PostHost'] . '/' . $this->file_name;
        }
        $model->update(false);
        return true;
    }

    /**
     * 添加轮播
     * @return bool
     */
    public function create()
    {
        $model = new HomepageWapAR;
        $model->setAttributes($this->getAttributes(['file_name', 'product_url', 'sort']), false);
        if ($this->file_name) {
            $model->img_url = \Yii::$app->params['OSS_PostHost'] . '/' . $this->file_name;
        }
        $model->insert(false);
        return true;
    }

    /**
     * 删除
     * @return bool
     */
    public function delete()
    {
        HomepageWapAR::deleteAll(['id' => $this->id]);
        return true;
    }

    /**
     * 排序
     * @return bool
     */
    public function sort()
    {
        if(!is_array($this->sort_items)){
            $this->addError('sort_items', 9002);
            return false;
        }
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            foreach ($this->sort_items as $sort=>$id){
                HomepageWapAR::updateAll(['sort' => ++$sort], ['id' => $id]);
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e){
            $transaction->rollBack();
            $this->addError('sort_items', 9002);
            return false;
        }
    }
}