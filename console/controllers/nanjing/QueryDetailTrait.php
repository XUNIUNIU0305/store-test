<?php
namespace console\controllers\nanjing;

use Yii;
use common\models\parts\trade\recharge\nanjing\Nanjing;

trait QueryDetailTrait{

    public function actionQueryDetail(string $from, string $to){
        $nanjing = new Nanjing;
        //TODO
    }
}
