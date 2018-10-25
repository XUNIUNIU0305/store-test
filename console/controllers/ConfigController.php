<?php
namespace console\controllers;

use Yii;
use console\controllers\basic\Controller;

class ConfigController extends Controller{

    public function actionRefreshSchema(string $tableName = null){
        if(is_null($tableName)){
            Yii::$app->db->schema->refresh();
        }else{
            Yii::$app->db->schema->refreshTableSchema($tableName);
        }
        $this->stdout("Refresh DONE!\n");
        return 0;
    }
}
