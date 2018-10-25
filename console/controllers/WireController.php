<?php
/**
 * Created by PhpStorm.
 * User: tangzhaofeng
 * Date: 18-8-23
 * Time: 下午5:49
 */
namespace console\controllers;

use Yii;
use console\controllers\basic\Controller;
use common\ActiveRecord\WireCarBrandAR;
use common\ActiveRecord\WireCarTypeAR;
use common\ActiveRecord\WireCarWireAR;
use yii\db\ActiveRecord;
use yii\helpers\Console;


class WireController extends Controller
{
    /**
     * 导入皇市线束表csv
     */
    public function actionImport($upfile)
    {
        $file = fopen($upfile, 'r');
        while(!feof($file)){
            $data[] = fgetcsv($file);

        }
        fclose($file);
        $sql=[];
        array_shift($data);
        foreach($data as $k =>$v) {
            if(!$v[5] == ''){
                $sql[] = $v;
                $brand[] = $v[1];
                $wire[] = intval($v[5]);
            }
        }
        $brand = array_values(array_unique($brand));
        $wire = array_unique($wire);
        sort($wire);
    try{
        $transaction = Yii::$app->db->beginTransaction();
        WireCarBrandAR::deleteAll();
        WireCarTypeAR::deleteAll();
        WireCarWireAR::deleteAll();

        foreach ( $wire as $s) {
            $wire_a[] = ['id'=>$s,'name' => "线束".$s];
        }

        foreach ( $brand as $k => $b ) {
            $brand_a[] = ['id' => $k+1, 'name' => $b];
            foreach ($sql as $t) {
                if ($t[1] == $b) {
                    $type_a[] = [
                        'brand_id' => $k+1,
                        'manufacturer' => $t[2],
                        'name' => $t[3],
                        'style' => $t[4],
                        'wire_id' => $t[5],
                        'remarks' => $t[6],
                    ];
                }
            }
        }
        Yii::$app->db->createCommand()->batchInsert(WireCarWireAR::tableName(),[
            'id',
            'name',
        ],$wire_a)->execute();
        Yii::$app->db->createCommand()->batchInsert(WireCarBrandAR::tableName(),[
            'id',
            'name'
        ],$brand_a)->execute();

        Yii::$app->db->createCommand()->batchInsert(WireCarTypeAR::tableName(), [
            'brand_id',
            'manufacturer',
            'name',
            'style',
            'wire_id',
            'remarks'
        ], $type_a)->execute();
        $transaction->commit();
    }catch (\Exception $e){
        $transaction->rollBack();
    }
       var_dump($data);
    }

}
