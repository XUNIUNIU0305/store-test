<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-11-6
 * Time: 下午3:08
 */

namespace admin\modules\homepage\models;


use common\ActiveRecord\HomepageKeywordsAR;
use common\models\Model;

class KeywordsModel extends Model
{
    const SCE_GET_LIST = 'get_list';
    const SCE_UPDATE = 'update';
    const SCE_DELETE = 'delete';
    const SCE_CREATE = 'create';
    const MAX = 10;

    public $id;
    public $name;
    public $sort = 99;

    public function scenarios()
    {
        return [
            self::SCE_GET_LIST => [],
            self::SCE_CREATE   => [
                'name',
                'sort'
            ],
            self::SCE_UPDATE => [
                'id',
                'name',
                'sort'
            ],
            self::SCE_DELETE => [
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
                'max' => 30,
                'message' => 9002
            ],
            [
                ['name', 'id'],
                'required',
                'message' => 9001
            ],
            [
                ['id'],
                'integer',
                'min'     => 1,
                'tooSmall' => 9002,
                'message' => 9002
            ]
        ];
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getList()
    {
        return HomepageKeywordsAR::find()
            ->select(['id', 'name'])
            ->orderBy('id desc')
            ->asArray()->all();
    }

    /**
     * @return bool
     */
    public function create()
    {
        $model       = new HomepageKeywordsAR;
        if(current($model->find()->select('count(*) as count')->asArray()->all())['count'] < self::MAX) {
            $model->name = $this->name;
            $model->sort = $this->sort;
            $model->insert(false);
            return true;            
        }
        $this->addError('attribute', 5401);
        return false;
    }

    /**
     * @return bool
     */
    public function update()
    {
        if ($model = HomepageKeywordsAR::findOne($this->id)) {
            $model->setAttributes($this->getAttributes(['name', 'sort']), false);
            $model->update(false);
            return true;
        }
        $this->addError('attribute', 9001);
        return false;
    }

    /**
     * @return bool
     */
    public function delete()
    {
        HomepageKeywordsAR::deleteAll(['id' => $this->id]);
        return true;
    }
}