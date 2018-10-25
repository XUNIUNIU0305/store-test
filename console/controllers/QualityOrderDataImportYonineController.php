<?php
/**
 * Created by PhpStorm.
 * User: forrest
 * Date: 12/07/18
 * Time: 13:48
 */
namespace console\controllers;

use common\ActiveRecord\BusinessAreaAR;
use common\ActiveRecord\BusinessAreaTechnicanAR;
use common\ActiveRecord\CarBrandAR;
use common\ActiveRecord\CarTypeAR;
use common\components\handler\quality\QualityOrderHandler;
use Yii;
use common\ActiveRecord\BusinessUserAR;
use common\ActiveRecord\QualityImportLogYonineAR;
use console\controllers\basic\Controller;

class QualityOrderDataImportYonineController extends Controller
{
    private $_fileDirName = __DIR__ . '/quality';

    public function actionIndex()
    {
        $files = [];
        if (is_dir($this->_fileDirName)) {
            $handle = opendir($this->_fileDirName);
            while (false !== ($file = readdir($handle))) {
                if ($file != '.' && $file != '..') {
                    array_unshift($files, $this->_fileDirName .'/'. $file);
                }
            }
        }

        foreach ($files as $file) {
            if (!preg_match('/\.xlsx$/', $file)) {
                echo "don't support the file format about: $file.\n";
                continue;
            }
            $this->handler($file);
        }
    }

    public function handler($excelFile)
    {
        if ($datas = $this->parseExcel($excelFile, 1, 10000)) {
            foreach ($datas as $k => $data) {
                // 过滤字段值
                $data = $this->fieldFilter($data);

                $data['服务商账号'] = 65962377;
                if (empty($data['服务商账号'])) {
                    $data['服务商账号'] = 0;
                }

                // 车主
                if (empty($data['车主姓名']) || empty($data['车主电话'])) {
                    $message = "车主姓名或手机不能为空";
                    $this->addInvalidData($data, $k, $message);
                    continue;
                }
                $data['车主电话'] = preg_replace('/[^0-9]/', '', $data['车主电话']);

                // 手机号
                if (!preg_match('/^1[0-9]{10}$/', $data['车主电话'])) {
                    $message = "手机号格式错误";
                    $this->addInvalidData($data, $k, $message);
                    continue;
                }

                // 施工相关
                if (empty($data['施工车牌号']) && empty($data['施工车架号'])) {
                    $message = "车牌号和车架号不能都为空";
                    $this->addInvalidData($data, $k, $message);
                    continue;
                }
                $data['施工车牌号'] = strtoupper($data['施工车牌号']);
                $data['施工车架号'] = strtoupper($data['施工车架号']);

                if ($data['施工车牌号'] == $data['施工车架号']) {
                    if (strlen($data['施工车架号']) == 17) {
                        // 只验证车架号
                        if (!preg_match('/^[0-9A-Z]+$/i', $data['施工车架号'])) {
                            $message = "车架号有非法字符";
                            $this->addInvalidData($data, $k, $message);
                            continue;
                        }
                        $data['施工车牌号'] = '';
                    } else {
                        // 只验证车牌号
                        if (mb_strlen($data['施工车牌号']) != 7 && mb_strlen($data['施工车牌号']) != 8) {
                            $message = "车牌号不为7位或8位";
                            $this->addInvalidData($data, $k, $message);
                            continue;
                        }
                        if (!preg_match('/[\x80-\xff][A-Z][a-z0-9]{5}/i', $data['施工车牌号'])) {
                            $message = "车牌号格式错误";
                            $this->addInvalidData($data, $k, $message);
                            continue;
                        }
                        $data['施工车架号'] = '';
                    }
                } else {
                    // 过滤车牌拍号和车架号
                    if (!empty($data['施工车架号'])) {
                        if (!preg_match('/^[0-9A-Z]+$/i', $data['施工车架号'])) {
                            $message = "车架号有非法字符";
                            $this->addInvalidData($data, $k, $message);
                            continue;
                        } else {
                            if (mb_strlen($data['施工车架号']) != 17) {
                                $message = "车架号长度不为17位";
                                $this->addInvalidData($data, $k, $message);
                                continue;
                            }
                        }
                    }
                    if (!empty($data['施工车牌号'])) {
                        if (mb_strlen($data['施工车牌号']) != 7 && mb_strlen($data['施工车牌号']) != 8) {
                            $message = "车牌号不为7位或8位";
                            $this->addInvalidData($data, $k, $message);
                            continue;
                        }
                    }
                    if (!empty($data['施工车牌号']) && !preg_match('/[\x80-\xff][A-Z][a-z0-9]{5}/i', $data['施工车牌号'])) {
                        $message = "车牌号格式错误";
                        $this->addInvalidData($data, $k, $message);
                        continue;
                    }
                    if (empty($data['施工车架号'])) {
                        $data['施工车架号'] = '';
                    }
                    if (empty($data['施工车牌号'])) {
                        $data['施工车牌号'] = '';
                    }
                }

                if (empty($data['服务商账号'])) {
                    $businessUser = [
                        'id' => 0,
                        'business_area_id' => 0,
                        'top_business_area_id' => 0,
                    ];
                } else {
                    $businessUser = BusinessUserAR::find()->select(['id', 'business_area_id', 'top_business_area_id'])
                        ->where(['account' => $data['服务商账号']])->asArray()->one();
                    if (!$businessUser) {
                        $message = "不匹配的服务商账号";
                        $this->addInvalidData($data, $k, $message);
                        continue;
                    }
                }

                $items = $this->getItems($data, $businessUser['business_area_id']);
                if ($items === false) {
                    $message = "施工员姓名或卷芯号或局部施工选项错误";
                    $this->addInvalidData($data, $k, $message);
                    continue;
                }

                $carBrandId = CarBrandAR::find()->select(['id'])->where(['name' => $data['车辆品牌']])->scalar();
                if (!$carBrandId) {
                    $message = "车品牌库中无该车品牌";
                    $this->addInvalidData($data, $k, $message);
                    unset($carBrandId);
                    continue;
                }
//                $carTypeId = CarTypeAR::find()->select(['id'])->where(['name' => $data['车辆型号']])->scalar();
//                if (!CarTypeAR::find()->where(['id' => $carTypeId, 'brand_id' => $carBrandId])->exists()) {
//                    $message = "车品牌和车类型不匹配";
//                    $this->addInvalidData($data, $k, $message);
//                    unset($carBrandId);
//                    unset($carTypeId);
//                    continue;
//                }

                $orderData = [
                    'owner_name' => $data['车主姓名'],
                    'owner_mobile' => $data['车主电话'],
                    'owner_telephone' => '',
                    'owner_email' => '',
                    'owner_address' => '',
                    'car_number' => $data['施工车牌号'],
                    'car_frame' => $data['施工车架号'],
                    'car_brand_id' => $carBrandId ?? 0,
                    'car_type_id' => $carTypeId ?? 0,
                    'car_price_range' => '',
                    'price' => 0,
                    'construct_unit' => $data['施工门店名称'],
                    'construct_time' => strtotime($data['开始施工时间']),
                    'finished_time' => strtotime($data['开始施工时间']),
                    'business_user_id' => $businessUser['id'],
                    'business_area_id' => $businessUser['business_area_id'],
                    'business_top_area_id' => $businessUser['top_business_area_id'],
                ];

                if (QualityOrderHandler::create($orderData, $items, $orderCode)) {
                    try {
                        echo "$k 质保单生成成功: $orderCode\n";
                        $this->addNormalData($data, $orderCode);
                    } catch (\Exception $e) {
                    }
                } else {
                    try {
                        $message = "未知错误导致质保单生成失败";
                        $this->addInvalidData($data, $k, $message);
                        echo "$k 质保单生成失败\n";
                    } catch (\Exception $e) {
                    }
                }
                unset($orderData);
                unset($message);
                unset($businessUser);
                unset($items);
                unset($data);
                unset($carBrandId);
                unset($carTypeId);
            }
        }

    }

    /**
     * 读取 .xlsx 文件并返回数组
     * @param string $absoluteFilename only support .xlsx file
     * @return array | false
     * @author forrestgao
     */
    private function parseExcel($absoluteFilename, $startRow = 1, $endRow = null)
    {
        ini_set("memory_limit", "8192M");

        $excelReader = \PHPExcel_IOFactory::createReader("Excel2007");
        // $excelReader = \PHPExcel_IOFactory::createReader("Excel5");
        $excelReader->setReadDataOnly(true);

        //如果有指定行数，则设置过滤器
        if ($startRow && $endRow) {
            // $perf           = new PHPExcelReadFilter();
            $perf = $this->getAnonymousClass('PHPExcelReadFilter');
            $perf->startRow = $startRow;
            $perf->endRow   = $endRow;
            $excelReader->setReadFilter($perf);
        }

        $phpexcel    = $excelReader->load($absoluteFilename);
        $activeSheet = $phpexcel->getActiveSheet();
        if (!$endRow) {
            $endRow = $activeSheet->getHighestRow(); //总行数
        }

        $datas = [];
        try {
            foreach ($phpexcel->getWorksheetIterator() as $sheet) {
                $j = 0;
                foreach ($sheet->getRowIterator() as $row) {
                    if ($row->getRowIndex() < 2) {
                        $col = [];
                        $i = 0;
                        foreach ($row->getCellIterator() as $cell) {
                            $col[$i++] = $cell->getValue();
                        }
                        continue;
                    }

                    $tmpRow = [];
                    $i = 0;
                    foreach ($row->getCellIterator() as $cell) {
                        if ($i == 3) {
                            // 处理单元格格式
                            $tmpRow[$col[$i++]] = \PHPExcel_Style_NumberFormat::toFormattedString($cell->getValue(), \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
                        } else {
                            $tmpRow[$col[$i++]] = $cell->getValue();
                        }
                    }
                    $datas[$j++] = $tmpRow;
                }
            }
        } catch (\Exception $e) {
            return false;
        }
        if (is_null($datas) || empty($datas) || !is_array($datas)) {
            return false;
        }
        if (!$this->hasField(current($datas))) {
            echo "excel 表缺少必要字段\n";
            return 0;
        }
        return $datas;
    }

    // 判断 excel 文件是否包含必要字段
    public function hasField($data)
    {
        if (!array_key_exists('施工门店名称', $data)
            || !array_key_exists('施工车牌号', $data)
            || !array_key_exists('施工车架号', $data)
            || !array_key_exists('开始施工时间', $data)
            || !array_key_exists('车主姓名', $data)
            || !array_key_exists('车主电话', $data)
            || !array_key_exists('车辆品牌', $data)
            || !array_key_exists('车辆型号', $data)
            || !array_key_exists('施工类型', $data)
            || !array_key_exists('局部施工选项', $data)
            || !array_key_exists('卷芯号', $data)
            || !array_key_exists('施工员姓名', $data)
        ) {
            return false;
        }
        return true;
    }

    // 处理字段值
    public function fieldFilter($data)
    {
        return array_map(function ($field) {
            if (!is_string($field) && !is_numeric($field)) {
                return '';
            }
            return preg_replace('/[\s\　]/u', '', $field);
        }, $data);
    }

    // 解析 items
    public function getItems($data, $businessAreaId)
    {
        $items = [];
        $items[0]['place_id'] = 1;
        $items[0]['technician'] = $this->getTechnicianId($data['施工员姓名'], $businessAreaId);
        $items[0]['round_num'] = $data['卷芯号'];
        $items[0]['sales'] = '';

        if ($data['施工类型'] == '整车施工') {
            $items[0]['work_option'] = '';
        } else {
            $items[0]['work_option'] = '';
            preg_match_all('/\"(.*)\"/U', $data['局部施工选项'], $matches);
            if (isset($matches[1])) {
                $items[0]['work_option'] = implode(',', $matches[1]);
            }
        }

        if ($packageId = QualityOrderHandler::getQualityOrderPackageId($data['卷芯号'])) {
            $items[0]['package_id'] = $packageId;
        } else {
            return false;
        }

        $result = [];
        foreach ($items as $item) {
            if (!empty($item['round_num'])) {
                $item['round_num'] = strtoupper($item['round_num']);
                $result[] = $item;
            }
        }
        return $result;
    }

    // 添加技工
    public function getTechnicianId($name, $businessAreaId)
    {
        // 如果有
        if ($id = BusinessAreaTechnicanAR::find()->select(['id'])->where(['name' => $name, 'business_area_id' => $businessAreaId])->scalar()) {
            return $id;
        } else {
            $chuangzhihuiAreaId = BusinessAreaAR::find()->select(['id'])->where(['name' => '创智汇', 'level' => 4])->scalar();
            if ($id = BusinessAreaTechnicanAR::find()->select(['id'])->where(['name' => $name, 'business_area_id' => $chuangzhihuiAreaId])->scalar()) {
                return $id;
            }
            $data = [
                'name' => $name,
                'mobile' => 0,
                'business_area_id' =>  (!empty($businessAreaId) ? $businessAreaId : $chuangzhihuiAreaId)
            ];
            if ($id = Yii::$app->RQ->AR(new BusinessAreaTechnicanAR())->insert($data, 'throw')) {
                return $id;
            } else {
                return 0;
            }
        }
    }

    // 记录非正常数据
    public function addInvalidData($data, $k, $message)
    {
        $data['开始施工时间'] = substr($data['开始施工时间'], 0, 10);
        $data = [
            'business_user_account' => $data['服务商账号'],
            'car_number' => $data['施工车牌号'],
            'car_frame' => $data['施工车架号'],
            'owner_name' => $data['车主姓名'],
            'owner_mobile' => $data['车主电话'],
            'construct_time' => $data['开始施工时间'],
            'car_brand' => $data['车辆品牌'],
            'car_type' => $data['车辆型号'],
            'construct_unit' => $data['施工门店名称'],
            'work_type' => $data['施工类型'],
            'work_option' => $data['局部施工选项'],
            'code' => $data['卷芯号'],
            'technician' => $data['施工员姓名'],
            'error_message' => $message,
        ];
        if (Yii::$app->RQ->AR(new QualityImportLogYonineAR())->insert($data, 'throw')) {
            echo "$k 非法数据存入成功\n";
        } else {
            echo "$k 非法数据存入失败\n";
        }
    }

    // 记录录入数据
    public function addNormalData($data, $orderCode)
    {
        $data['开始施工时间'] = substr($data['开始施工时间'], 0, 10);
        $data = [
            'order_code' => $orderCode,
            'business_user_account' => $data['服务商账号'],
            'car_number' => $data['施工车牌号'],
            'car_frame' => $data['施工车架号'],
            'owner_name' => $data['车主姓名'],
            'owner_mobile' => $data['车主电话'],
            'construct_time' => $data['开始施工时间'],
            'car_brand' => $data['车辆品牌'],
            'car_type' => $data['车辆型号'],
            'construct_unit' => $data['施工门店名称'],
            'work_type' => $data['施工类型'],
            'work_option' => $data['局部施工选项'],
            'code' => $data['卷芯号'],
            'technician' => $data['施工员姓名'],
            'error_message' => '',
        ];
        Yii::$app->RQ->AR(new QualityImportLogYonineAR())->insert($data, 'throw');
    }

    // excel过滤器匿名类
    public function getAnonymousClass($className)
    {
        return new class ($className) implements \PHPExcel_Reader_IReadFilter {
            public $startRow = 1;
            public $endRow;
            public function readCell($column, $row, $worksheetName = '') {
                //如果endRow没有设置表示读取全部
                if (!$this->endRow) {
                    return true;
                }
                //只读取指定的行
                if ($row >= $this->startRow && $row <= $this->endRow) {
                    return true;
                }
                return false;
            }
        };
    }
}