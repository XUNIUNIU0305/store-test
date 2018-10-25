<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-30
 * Time: 下午3:03
 */

namespace admin\modules\homepage\models;


use common\ActiveRecord\HomepageColumnAR;
use common\ActiveRecord\HomepageColumnBrandAR;
use common\ActiveRecord\HomepageColumnItemAR;
use common\models\Model;
use common\models\parts\homepage\Column;

class ColumnModel extends Model
{
    const SCE_COLUMN_LIST = 'column_list';
    const SCE_COLUMN_ADD = 'column_add';
    const SCE_COLUMN_UPDATE = 'column_update';
    const SCE_COLUMN_DELETE = 'column_delete';
    const MAX_COLUMNS_COUNT = 8;

    public $name;

    public $id;

    public function scenarios()
    {
        return [
            self::SCE_COLUMN_LIST => [],
            self::SCE_COLUMN_ADD => [
                'name'
            ],
            self::SCE_COLUMN_UPDATE => [
                'id',
                'name'
            ],
            self::SCE_COLUMN_DELETE => [
                'id'
            ]
        ];
    }

    public function rules()
    {
        return [
            [
                ['name'],
                'string',
                'length' => [1, 100],
                'message' => 9002
            ],
            [
                ['id', 'name'],
                'required',
                'message' => 9001
            ],
            [
                ['id'],
                'integer',
                'min' => 1,
                'message' => 9002
            ]
        ];
    }

    /**
     * 获取一级栏目
     * @return array|\yii\db\ActiveRecord[]
     */
    public function columnList()
    {
        return HomepageColumnAR::find()
            ->select(['id', 'name'])
            ->where(['status' => HomepageColumnAR::STATUS_ACTIVE])
            ->all();
    }

    /**
     * 添加栏目
     * @return bool
     */
    public function columnAdd()
    {
        $column = new HomepageColumnAR;
        $column->name = $this->name;
        if(current($column->find()->select(['count(*) as count'])->asArray()->all())['count'] >= self::MAX_COLUMNS_COUNT) {
            $this->addError('column', 5410);
            return false;
        }
        $column->insert(false);
        return true;
    }

    /**
     * 修改栏目
     * @return bool
     */
    public function columnUpdate()
    {
        try{
            $column = Column::getInstanceById($this->id);
            $column->updateName($this->name);
            return true;
        } catch (\Exception $e){
            $this->addError('id', 9002);
            return false;
        }
    }

    /**
     * 删除栏目
     * @return bool
     */
    public function columnDelete()
    {
        try{
            if(HomepageColumnItemAR::find()->where(['column_id' => $this->id])->count()){
                $this->addError('id', 5395);
                return false;
            }
            if(HomepageColumnBrandAR::find()->where(['column_id' => $this->id])->count()){
                $this->addError('id', 5395);
                return false;
            }
            HomepageColumnAR::deleteAll(['id' => $this->id]);
            return true;
        } catch (\Exception $e){
            $this->addError('id', 9002);
            return false;
        }
    }
}