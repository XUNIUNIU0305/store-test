<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-10
 * Time: 下午1:51
 */

namespace mobile\modules\lottery\controllers;


use mobile\modules\lottery\models\GameModel;
use yii\helpers\Url;

class GameController extends Controller
{
    protected $access = [
        'arms'  => ['@', 'get'],
        'open'  => ['@', 'post'],
        'index' => ['@', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'arms'  => GameModel::SCE_ARMS,
        '_model'    => GameModel::class
    ];

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 打开礼包
     * @return \common\controllers\json
     */
    public function actionOpen()
    {
        $model = new GameModel([
            'scenario' => GameModel::SCE_OPEN,
            'attributes' => \Yii::$app->request->post()
        ]);

        if($res = $model->process()){
            /** $res ChanceItem */
            return $this->success(['url' => Url::toRoute(['gift/index' , '#' => $res])]);
        }
        return $this->failure($model->getErrorCode());
    }
}