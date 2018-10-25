<?php
namespace business\modules\site\controllers;

use business\controllers\Controller;
use business\modules\site\models\PromoterModel;
use Yii;

class PromoterController extends Controller {
    protected $access = [
        'index'=>['!50','get'],
        'list'=>['!50','get'],
        'add'=>['!50','post'],
        'delete'=>['!50','post'],
        'update'=>['!50','post'],
        'download'=>['!50','get'],
        'invite-log'=>['!50','get'],
        'stream-log'=>['!50','get'],
        'count'=>['!50','get'],
        'stream-count'=>['!50','get'],
        'code-title'=>['!50','get'],
    ];
    protected $actionUsingDefaultProcess = [
        'list'=>PromoterModel::SCE_LIST,
        'add'=>PromoterModel::SCE_ADD,
        'delete'=>PromoterModel::SCE_DELETE,
        'update'=>PromoterModel::SCE_UPDATE,
        'invite-log'=>PromoterModel::SCE_INVITE_LOG,
        'stream-log'=>PromoterModel::SCE_STREAM_LOG,
        'count'=>PromoterModel::SCE_STATUS_COUNT,
        'stream-count'=>PromoterModel::SCE_STREAM_COUNT,
        'code-title'=>PromoterModel::SCE_CODE_TITLE,
        '_model'=>'business\modules\site\models\PromoterModel'
     ];

    public function actionIndex(){
        return $this->render('index');
    }

    public function actionInvite(){
        return $this->render('invite');
    }

    public function actionReview(){
        return $this->render('review');
    }

    public function actionStream(){
        return $this->render('stream');
    }

    public function actionDownload(){
        $model = new PromoterModel([
            'scenario' => PromoterModel::SCE_DOWNLOAD,
            'attributes' => Yii::$app->request->get(),
        ]);
        if ($model->getQrCode() !== false){
            header('Content-type: application/octet-stream; charset=utf8');
            Header("Accept-Ranges: bytes");
            header('Content-Disposition: attachment; filename='.$model->id .$model->codeTitle()['title'].'.png');
            header('Content-type: image/jpeg');
            return $model->getQrCode();
        }
        return  $this->failure($model->errorCode);
    }

}
