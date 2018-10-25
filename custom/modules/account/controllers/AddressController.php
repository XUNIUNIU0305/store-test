<?php
namespace custom\modules\account\controllers;

use Yii;
use common\controllers\Controller;
use custom\modules\account\models\AddressModel;

class AddressController extends Controller{

    protected $access = [
        'index' => ['@', 'get'],
        'add' => ['@', 'post'],
        'list' => ['@', 'get'],
        'remove' => ['@', 'post'],
        'default' => ['@', 'post'],
        'edit' => ['@', 'post'],
    ];

    public function actionIndex(){
        return $this->render('index');
    }

    public function actionAdd(){
        $addressModel = new AddressModel([
            'scenario' => AddressModel::SCE_ADD_ADDRESS,
            'attributes' => Yii::$app->request->post(),
        ]);
        if($addressModel->process()){
            return $this->success([]);
        }else{
            return $this->failure($addressModel->getErrorCode());
        }
    }

    public function actionList(){
        return $this->success(AddressModel::displayList(), false);
    }

    public function actionRemove(){
        $addressModel = new AddressModel([
            'scenario' => AddressModel::SCE_REMOVE_ADDRESS,
            'attributes' => Yii::$app->request->post(),
        ]);
        if($addressModel->process()){
            return $this->success([]);
        }else{
            return $this->failure($addressModel->getErrorCode());
        }
    }

    public function actionDefault(){
        $addressModel = new AddressModel([
            'scenario' => AddressModel::SCE_SET_DEFAULT,
            'attributes' => Yii::$app->request->post(),
        ]);
        if($addressModel->process()){
            return $this->success([]);
        }else{
            return $this->failure($addressModel->getErrorCode());
        }
    }

    public function actionEdit(){
        $addressModel = new AddressModel([
            'scenario' => AddressModel::SCE_EDIT_ADDRESS,
            'attributes' => Yii::$app->request->post(),
        ]);
        if($addressModel->process()){
            return $this->success([]);
        }else{
            return $this->failure($addressModel->getErrorCode());
        }
    }
}
