<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-23
 * Time: 上午11:21
 */

namespace supply\modules\express\models;


use common\ActiveRecord\ExpressCorporationAR;
use common\ActiveRecord\SupplyUserExpressAR;
use common\models\Model;
use supply\modules\express\models\object\SupplyUserExpress;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;

class IndexModel extends Model
{
    const SCE_PAGE = 'page';
    const SCE_SEARCH = 'search';
    const SCE_ADD = 'add';
    const SCE_DELETE = 'delete';

    public $page = 1;

    public $page_size = 10;

    public $name;

    public $items;

    public function scenarios()
    {
        return [
            self::SCE_PAGE => [
                'page', 'page_size', 'name'
            ],
            self::SCE_SEARCH => [
                'name'
            ],
            self::SCE_ADD => [
                'items'
            ],
            self::SCE_DELETE => [
                'items'
            ]
        ];
    }

    public function rules()
    {
        return [
            [
                ['page', 'page_size'],
                'integer',
                'min' => 1,
                'message' => 9002
            ],
            [
                ['name'],
                'string',
                'message' => 9002
            ],
            [
                ['items'],
                'required',
                'message' => 9001
            ],
            [
                ['items'],
                'each',
                'rule' => [
                    'integer'
                ],
                'message' => 9002
            ]
        ];
    }

    /**
     * 分页
     * @return array|\yii\db\ActiveRecord[]
     */
    public function page()
    {
        $common = SupplyUserExpressAR::find()
            ->select(['express_id'])
            ->where(['user_id' => \Yii::$app->user->id])
            ->column();

        $models = ExpressCorporationAR::find()
            ->select(['id', 'name', 'first_char'])
            ->filterWhere(['like', 'name', $this->name])
            ->orderBy('first_char asc')
            ->asArray()->all();

        $temp = $temp2 = [];

        foreach ($models as $model){
            if(in_array($model['id'], $common)){
                $model['is_common'] = true;
                $temp[] = $model;
            } else {
                $model['is_common'] = false;
                $temp2[]= $model;
            }
        }
        
        $models = array_merge($temp, $temp2);

        $totalCount = count($models);
        $page = new Pagination([
            'totalCount' => count($models),
            'pageSize' => $this->page_size
        ]);

        $items = [];
        if ($page->getPageSize() > 0) {
            $items = array_slice($models, $page->getOffset(), $page->getLimit(), true);
        }

        return [
            'items' => $items,
            'count' => $this->page_size,
            'total_count' => $totalCount
        ];
    }

    /**
     * 搜索自动完成
     * @return array|\yii\db\ActiveRecord[]
     */
    public function search()
    {
        if(empty($this->name)) return [];
        $models = ExpressCorporationAR::find()
            ->select(['id', 'name', 'first_char'])
            ->where(['like', 'name', $this->name])
            ->limit(10)
            ->asArray()->all();

        return $models;
    }

    /**
     * 添加常用
     * @return bool
     */
    public function add()
    {
        $count = SupplyUserExpress::queryCount($uid = \Yii::$app->user->id);
        if($count + count($this->items) > SupplyUserExpress::MAX_NUMBER){
            $this->addError('items', 1220);
            return false;
        }
        $num = ExpressCorporationAR::find()
            ->where(['id' => $this->items])
            ->count();
        if($num != count($this->items)){
            $this->addError('items', 1221);
            return false;
        }
        $data = [];
        foreach ($this->items as $id){
            $data[] = [ $uid, $id];
        }
        $trans = \Yii::$app->db->beginTransaction();
        try {
            SupplyUserExpressAR::deleteAll(['user_id' => $uid, 'express_id' => $this->items]);
            foreach ($this->items as $id){
                $model = new SupplyUserExpressAR([
                    'user_id' => $uid,
                    'express_id' => $id
                ]);
                $model->insert(false);
            }
            $trans->commit();
            return true;
        } catch (\Exception $e){
            $trans->rollBack();
            $this->addError('items', 1222);
            return false;
        }
    }

    /**
     * 删除常用
     * @return bool
     */
    public function delete()
    {
        try{
            SupplyUserExpressAR::deleteAll(['user_id' => \Yii::$app->user->id, 'express_id' => $this->items]);
            return true;
        } catch (\Exception $e){
            $this->addError('items', 1222);
            return false;
        }
    }
}