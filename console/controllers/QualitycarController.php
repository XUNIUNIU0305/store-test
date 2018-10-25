<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/5/31
 * Time: 下午2:02
 */

namespace console\controllers;


use common\ActiveRecord\QualityCarAR;
use common\ActiveRecord\QualityCarOptionAR;
use yii\console\Controller;
use Yii;

class QualitycarController extends Controller
{

    public $file_name = '/Users/lishuang/Documents/quality_car.xls';
    public $read_type = 'Excel2007';

    public function actionInit()
    {
        //第一种方法
        //建立reader对象 ，分别用两个不同的类对象读取2007和2003版本的excel文件
        $PHPReader = new \PHPExcel_Reader_Excel2007();
        if(!$PHPReader->canRead($this->file_name))
        {
            $PHPReader = new \PHPExcel_Reader_Excel5();
            if( ! $PHPReader->canRead($this->file_name)){
                echo 'no Excel';
                return ;
            }
        }

        $PHPExcel = $PHPReader->load($this->file_name); //读取文件
        $currentSheet = $PHPExcel->getSheet(0); //读取第一个工作簿
        $allRow = $currentSheet->getHighestRow(); // 所有行
        for ($rowIndex = 1; $rowIndex <= $allRow; $rowIndex++)
        {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $brandName = $currentSheet->getCell('A'.$rowIndex)->getValue();
                $typeName = $currentSheet->getCell('B'.$rowIndex)->getValue();
                $factor = $currentSheet->getCell('C'.$rowIndex)->getValue();
                if ( Yii::$app->RQ->AR(new QualityCarAR())->exist(['name'=>$brandName])){
                    $brandId =  Yii::$app->RQ->AR(new QualityCarAR())->scalar([
                        'select'=>['id'],
                        'where'=>['name'=>$brandName],
                    ]);
                }else{
                    $brandId =  Yii::$app->RQ->AR(new QualityCarAR())->insert([
                        'name'=>$brandName,
                    ]);
                }
                $typeId =  Yii::$app->RQ->AR(new QualityCarAR())->insert([
                    'name'=>$typeName,
                    'parent'=>$brandId,
                ]);

                Yii::$app->RQ->AR(new QualityCarOptionAR())->insert([
                    'brand_id'=>$brandId,
                    'type_id'=>$typeId,
                    'factor'=>$factor,
                    'unit_price'=>10,
                ]);
                $transaction->commit();

            }catch (\Exception $exception){
                $transaction->rollBack();
                echo $exception->getMessage();
            }
        }
    }
}