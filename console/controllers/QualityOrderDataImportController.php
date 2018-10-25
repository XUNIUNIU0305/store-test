<?php
/**
 * Created by PhpStorm.
 * User: forrest
 * Date: 18/05/18
 * Time: 10:13
 */
namespace console\controllers;

use common\ActiveRecord\BusinessAreaAR;
use common\ActiveRecord\BusinessAreaTechnicanAR;
use common\ActiveRecord\BusinessUserAR;
use common\ActiveRecord\CarBrandAR;
use common\ActiveRecord\CarTypeAR;
use common\ActiveRecord\QualityImportLogAR;
use common\components\handler\quality\QualityOrderHandler;
use console\controllers\basic\Controller;
use Yii;

class QualityOrderDataImportController extends Controller
{
    private $fileDirName = __DIR__ . '/quality';

    public function actionIndex()
    {
        $files = [];
        if (is_dir($this->fileDirName)) {
            $handle = opendir($this->fileDirName);
            while (false !== ($file = readdir($handle))) {
                if ($file != '.' && $file != '..') {
                    array_unshift($files, $this->fileDirName .'/'. $file);
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
                // 去除空字段
                // array_pop($data);
                // 过滤字段值
                $data = $this->fieldFilter($data);

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
                    $message = "缺少膜与管芯号信息或膜与管芯号数据不匹配";
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
                    echo "$k 质保单生成成功: $orderCode\n";
                    $this->addNormalData($data, $orderCode);
                } else {
                    $message = "未知错误导致质保单生成失败";
                    $this->addInvalidData($data, $k, $message);
                    echo "$k 质保单生成失败\n";
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
                        if ($i == 5) {
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
        if (!array_key_exists('服务商账号', $data)
            || !array_key_exists('施工门店名称', $data)
            || !array_key_exists('施工车牌号', $data)
            || !array_key_exists('施工车架号', $data)
            || !array_key_exists('开始施工时间', $data)
            || !array_key_exists('车主姓名', $data)
            || !array_key_exists('车主电话', $data)
            || !array_key_exists('车辆品牌', $data)
            || !array_key_exists('车辆型号', $data)
            || !array_key_exists('前挡用膜', $data)
            || !array_key_exists('后挡用膜', $data)
            || !array_key_exists('左前用膜', $data)
            || !array_key_exists('左后用膜', $data)
            || !array_key_exists('右前用膜', $data)
            || !array_key_exists('右后用膜', $data)
            || !array_key_exists('天窗用膜', $data)
            || !array_key_exists('前挡膜卷芯号', $data)
            || !array_key_exists('后挡膜卷芯号', $data)
            || !array_key_exists('左前膜卷芯号', $data)
            || !array_key_exists('左后膜卷芯号', $data)
            || !array_key_exists('右前膜卷芯号', $data)
            || !array_key_exists('右后膜卷芯号', $data)
            || !array_key_exists('天窗膜卷芯号', $data)
            || !array_key_exists('前挡施工员', $data)
            || !array_key_exists('后挡施工员', $data)
            || !array_key_exists('左前施工员', $data)
            || !array_key_exists('左后施工员', $data)
            || !array_key_exists('右前施工员', $data)
            || !array_key_exists('右后施工员', $data)
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
        // 膜
        $membrane = [];
        $membrane[] = $data['前挡用膜'];
        $membrane[] = $data['后挡用膜'];
        $membrane[] = $data['左前用膜'];
        $membrane[] = $data['左后用膜'];
        $membrane[] = $data['右前用膜'];
        $membrane[] = $data['右后用膜'];
        $membrane[] = $data['天窗用膜'];
        $mCount = 0;
        foreach ($membrane as $value) {
            if (empty($value) || $value == '不施工') $mCount++;
        }
        if ($mCount == 7) {
            // echo "膜不为空或不全为不施工\n";
            return false;
        }

        $data['前挡膜卷芯号'] = strtoupper($data['前挡膜卷芯号']);
        $data['后挡膜卷芯号'] = strtoupper($data['后挡膜卷芯号']);
        $data['左前膜卷芯号'] = strtoupper($data['左前膜卷芯号']);
        $data['左后膜卷芯号'] = strtoupper($data['左后膜卷芯号']);
        $data['右前膜卷芯号'] = strtoupper($data['右前膜卷芯号']);
        $data['右后膜卷芯号'] = strtoupper($data['右后膜卷芯号']);
        $data['天窗膜卷芯号'] = strtoupper($data['天窗膜卷芯号']);
        // 膜卷芯号
        $itemCode = [];
        $itemCode[] = $data['前挡膜卷芯号'];
        $itemCode[] = $data['后挡膜卷芯号'];
        $itemCode[] = $data['左前膜卷芯号'];
        $itemCode[] = $data['左后膜卷芯号'];
        $itemCode[] = $data['右前膜卷芯号'];
        $itemCode[] = $data['右后膜卷芯号'];
        $itemCode[] = $data['天窗膜卷芯号'];
        $iCount = 0;
        foreach ($itemCode as $value) {
            if (!empty($value) && !preg_match('/^DH[A-Z0-9]+$/i', $value)) {
                return false;
            }
            if (empty($value)) $iCount++;
        }
        if ($iCount == 7) {
            // echo "膜卷芯号必须要有一个\n";
            return false;
        }

        // 施工员
        $technician = [];
        $technician[] = $data['前挡施工员'];
        $technician[] = $data['后挡施工员'];
        $technician[] = $data['左前施工员'];
        $technician[] = $data['左后施工员'];
        $technician[] = $data['右前施工员'];
        $technician[] = $data['右后施工员'];
        $technician[] = $data['天窗施工员'];
        $tCount = 0;
        foreach ($technician as $value) {
            if (empty($value)) $tCount++;
        }
        if ($tCount == 7) {
            // echo "施工员必须要有一个\n";
            return false;
        }

        $items = [];
        $items[0]['place_id'] = 3;
        $items[0]['technician'] = $this->getTechnicianId($data['前挡施工员'], $businessAreaId);
        $items[0]['round_num'] = $data['前挡膜卷芯号'];
        $items[0]['sales'] = '';
        if (empty($items[0]['round_num'])) {
            unset($items[0]);
        } else {
            if ($packageId = QualityOrderHandler::getQualityOrderPackageId($data['前挡膜卷芯号'])) {
                $items[0]['package_id'] = $packageId;
            } else {
                return false;
            }
        }
        if (!empty($data['前挡膜卷芯号']) && $data['前挡用膜'] == '不施工') {
            // echo "相同部位的膜与管芯号数据不对应\n";
            return false;
        }

        $items[1]['place_id'] = 4;
        $items[1]['technician'] = $this->getTechnicianId($data['后挡施工员'], $businessAreaId);
        $items[1]['round_num'] = $data['后挡膜卷芯号'];
        $items[1]['sales'] = '';
        if (empty($items[1]['round_num'])) {
            unset($items[1]);
        } else {
            if ($packageId = QualityOrderHandler::getQualityOrderPackageId($data['后挡膜卷芯号'])) {
                $items[1]['package_id'] = $packageId;
            } else {
                return false;
            }
        }
        if (!empty($data['后挡膜卷芯号']) && $data['后挡用膜'] == '不施工') {
            // echo "相同部位的膜与管芯号数据不对应\n";
            return false;
        }

        $items[2]['place_id'] = 5;
        $items[2]['technician'] = $this->getTechnicianId($data['左前施工员'], $businessAreaId);;
        $items[2]['round_num'] = $data['左前膜卷芯号'];
        $items[2]['sales'] = '';
        if (empty($items[2]['round_num'])) {
            unset($items[2]);
        } else {
            if ($packageId = QualityOrderHandler::getQualityOrderPackageId($data['左前膜卷芯号'])) {
                $items[2]['package_id'] = $packageId;
            } else {
                return false;
            }
        }
        if (!empty($data['左前膜卷芯号']) && $data['左前用膜'] == '不施工') {
            // echo "相同部位的膜与管芯号数据不对应\n";
            return false;
        }

        $items[3]['place_id'] = 6;
        $items[3]['technician'] = $this->getTechnicianId($data['右前施工员'], $businessAreaId);
        $items[3]['round_num'] = $data['右前膜卷芯号'];
        $items[3]['sales'] = '';
        if (empty($items[3]['round_num'])) {
            unset($items[3]);
        } else {
            if ($packageId = QualityOrderHandler::getQualityOrderPackageId($data['右前膜卷芯号'])) {
                $items[3]['package_id'] = $packageId;
            } else {
                return false;
            }
        }
        if (!empty($data['右前膜卷芯号']) && $data['右前用膜'] == '不施工') {
            // echo "相同部位的膜与管芯号数据不对应\n";
            return false;
        }

        $items[4]['place_id'] = 7;
        $items[4]['technician'] = $this->getTechnicianId($data['左后施工员'], $businessAreaId);
        $items[4]['round_num'] = $data['左后膜卷芯号'];
        $items[4]['sales'] = '';
        if (empty($items[4]['round_num'])) {
            unset($items[4]);
        } else {
            if ($packageId = QualityOrderHandler::getQualityOrderPackageId($data['左后膜卷芯号'])) {
                $items[4]['package_id'] = $packageId;
            } else {
                return false;
            }
        }
        if (!empty($data['左后膜卷芯号']) && $data['左后用膜'] == '不施工') {
            // echo "相同部位的膜与管芯号数据不对应\n";
            return false;
        }

        $items[5]['place_id'] = 8;
        $items[5]['technician'] = $this->getTechnicianId($data['右后施工员'], $businessAreaId);
        $items[5]['round_num'] = $data['右后膜卷芯号'];
        $items[5]['sales'] = '';
        if (empty($items[5]['round_num'])) {
            unset($items[5]);
        } else {
            if ($packageId = QualityOrderHandler::getQualityOrderPackageId($data['右后膜卷芯号'])) {
                $items[5]['package_id'] = $packageId;
            } else {
                return false;
            }
        }

        if (!empty($data['右后膜卷芯号']) && $data['右后用膜'] == '不施工') {
            // echo "相同部位的膜与管芯号数据不对应\n";
            return false;
        }

        $items[6]['place_id'] = 2;
        $items[6]['technician'] = $this->getTechnicianId($data['天窗施工员'], $businessAreaId);
        $items[6]['round_num'] = $data['天窗膜卷芯号'];
        $items[6]['sales'] = '';
        if (empty($items[6]['round_num'])) {
            unset($items[6]);
        } else {
            if ($packageId = QualityOrderHandler::getQualityOrderPackageId($data['天窗膜卷芯号'])) {
                $items[6]['package_id'] = $packageId;
            } else {
                return false;
            }
        }
        if (!empty($data['天窗膜卷芯号']) && $data['天窗用膜'] == '不施工') {
            // echo "相同部位的膜与管芯号数据不对应\n";
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
            'membrane_front' => $data['前挡用膜'],
            'membrane_back' => $data['后挡用膜'],
            'membrane_left_front' => $data['左前用膜'],
            'membrane_left_back' => $data['左后用膜'],
            'membrane_right_front' => $data['右前用膜'],
            'membrane_right_back' => $data['右后用膜'],
            'membrane_up' => $data['天窗用膜'],
            'code_front' => $data['前挡膜卷芯号'],
            'code_back' => $data['后挡膜卷芯号'],
            'code_left_front' => $data['左前膜卷芯号'],
            'code_left_back' => $data['左后膜卷芯号'],
            'code_right_front' => $data['右前膜卷芯号'],
            'code_right_back' => $data['右后膜卷芯号'],
            'code_up' => $data['天窗膜卷芯号'],
            'technician_front' => $data['前挡施工员'],
            'technician_back' => $data['后挡施工员'],
            'technician_left_front' => $data['左前施工员'],
            'technician_left_back' => $data['左后施工员'],
            'technician_right_front' => $data['右前施工员'],
            'technician_right_back' => $data['右后施工员'],
            'technician_up' => $data['天窗施工员'],
            'error_message' => $message,
        ];
        if (Yii::$app->RQ->AR(new QualityImportLogAR())->insert($data, 'throw')) {
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
            'membrane_front' => $data['前挡用膜'],
            'membrane_back' => $data['后挡用膜'],
            'membrane_left_front' => $data['左前用膜'],
            'membrane_left_back' => $data['左后用膜'],
            'membrane_right_front' => $data['右前用膜'],
            'membrane_right_back' => $data['右后用膜'],
            'membrane_up' => $data['天窗用膜'],
            'code_front' => $data['前挡膜卷芯号'],
            'code_back' => $data['后挡膜卷芯号'],
            'code_left_front' => $data['左前膜卷芯号'],
            'code_left_back' => $data['左后膜卷芯号'],
            'code_right_front' => $data['右前膜卷芯号'],
            'code_right_back' => $data['右后膜卷芯号'],
            'code_up' => $data['天窗膜卷芯号'],
            'technician_front' => $data['前挡施工员'],
            'technician_back' => $data['后挡施工员'],
            'technician_left_front' => $data['左前施工员'],
            'technician_left_back' => $data['左后施工员'],
            'technician_right_front' => $data['右前施工员'],
            'technician_right_back' => $data['右后施工员'],
            'technician_up' => $data['天窗施工员'],
            'error_message' => '',
        ];
        Yii::$app->RQ->AR(new QualityImportLogAR())->insert($data, 'throw');
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