<?php
namespace api\controllers;

use Yii;
use api\models\DistrictModel;

class DistrictController extends Controller{

    protected $access = [
        'province' => [null, 'get'],
        'city' => [null, 'get'],
        'district' => [null, 'get'],
    ];

    public function actionProvince(){
        return $this->success(DistrictModel::getProvince());
    }

    public function actionCity(){
        $DistrictModel = new DistrictModel([
            'scenario' => DistrictModel::SCE_GET_CITY,
            'attributes' => Yii::$app->request->get(),
        ]);
        $result = $DistrictModel->process();
        if($result !== false){
            return $this->success($result);
        }else{
            return $this->failure($DistrictModel->getErrorCode());
        }
    }

    public function actionDistrict(){
        $DistrictModel = new DistrictModel([
            'scenario' => DistrictModel::SCE_GET_DISTRICT,
            'attributes' => Yii::$app->request->get(),
        ]);
        $result = $DistrictModel->process();
        if($result !== false){
            return $this->success($result);
        }else{
            return $this->failure($DistrictModel->getErrorCode());
        }
    }
}
