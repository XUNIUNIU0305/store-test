<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 11:44
 */

namespace common\components\handler\quality;


use common\ActiveRecord\CarBrandAR;
use common\ActiveRecord\CarTypeAR;
use common\ActiveRecord\CustomUserAR;
use common\ActiveRecord\QualityCarAR;
use common\ActiveRecord\QualityOrderAR;
use common\ActiveRecord\QualityOrderItemAR;
use common\ActiveRecord\QualityPackageAR;
use common\ActiveRecord\QualityPriceAR;
use common\components\handler\Handler;
use common\models\parts\quality\BusinessAreaTechnican;
use common\models\parts\quality\QualityOrder;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;

class QualityOrderHandler extends Handler
{



    public static function getList(int $pageSize = 10, int $currentPage = 1,$topAreaId, int $type = null, string $keyword = "", string $startTime = null, string $endTime = null,BusinessAreaTechnican $technican=null)
    {
        $currentPage = $currentPage > 0 ? $currentPage : 1;
        $pageSize = $pageSize > 0 ? $pageSize : 1;
        $query = QualityOrderAR::find()->
            orderBy(['construct_time' => SORT_DESC])->
            asArray();
        switch($type){
            case 1://质保卡号
                $query->where(['like', 'code', $keyword]);
                break;

            case 2://产品序列号
                $qualityOrderIds = QualityOrderItemAR::find()->select(['quality_order_id'])->distinct()->where(['like', 'code', $keyword])->column();
                $query->where(['id' => $qualityOrderIds]);
                break;

            case 3://施工日期
                $query->where(['>=', 'construct_time', strtotime($startTime)]);
                $query->andWhere(['<=', 'construct_time', strtotime($endTime)]);
                break;

            case 4://按姓名
                $query->where(['like', 'owner_name', $keyword]);
                break;

            case 5://车牌号
                $query->where(['like', 'car_number', $keyword]);
                break;

            case 6://车架号
                $query->where(['like', 'car_frame', $keyword]);
                break;

            default:
                break;
        }

        if(!is_null($technican)){
            $query->andWhere(['custom_user_technician_id' => $technican->id]);
        }

        if($topAreaId != 0){
            $query->andWhere(['business_top_area_id' => $topAreaId]);
        }
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
        ]);

    }

    /**
     * @param array|null $orderData
     * @param array|null $orderItem
     * @param string $orderCode 传地址，用于返回订单号
     * @param string $return
     * @return bool
     */
    public static function create(array $orderData = null, array $orderItem = null, &$orderCode = "", $return = "throw")
    {
        if ($orderData == null || $orderItem == null) return false;
        //开启事务
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $orderCode = $orderData['code'] = self::createOrderCode();
            //写入订单信息
            if (!$order_id = Yii::$app->RQ->AR(new QualityOrderAR())->insert($orderData, $return)) {
                $transaction->rollBack();
                return false;
            };
            //写入订单详情
            if (!QualityOrderItemHandler::create($order_id, $orderItem)) {
                $transaction->rollBack();
                return false;
            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            $transaction->rollBack();
            return false;
        }


    }


    //创建订单号
    private static function createOrderCode()
    {
        $code = rand(100, 999) . time();
        if (QualityOrderAR::find()->where(['code' => $code])->exists()) {
            return self::createOrderCode();
        }
        return $code;
    }


    /**
     * 获取车主质保单信息列表
     * @param $order_code 质保单号
     */
    public static function getOwnerList($order_code)
    {
        $qualityOrder = QualityOrderAR::find()->where(
            ['code' => $order_code]
        )->asArray()->one();

        if (empty($qualityOrder)) {
            return false;
        }

        if (mb_strlen($qualityOrder['owner_name']) === 1) {
            $qualityOrder['owner_name'] .= '先生/女士';
        }

        if ($qualityOrder['construct_time']) {
            $qualityOrder['construct_time'] = date('Y.m.d', $qualityOrder['construct_time']);
        } else {
            $qualityOrder['construct_time'] = '无';
        }

        // 质保单信息
        $datas = [];
        $datas['order'] = [
            'quality_order' => $qualityOrder['code'],
            'construct_time' => $qualityOrder['construct_time']
        ];

        // 车主信息
        $datas['owner'] = [
            'owner_name' => $qualityOrder['owner_name'],
            'owner_mobile' => $qualityOrder['owner_mobile'],
            'owner_address' => $qualityOrder['owner_address'],
            'owner_telephone' => $qualityOrder['owner_telephone'],
            'owner_email' => $qualityOrder['owner_email'],
        ];
        $datas['owner'] = array_map(function ($field) {
            if (!$field) {
                return '无';
            }
            return preg_replace('/[\s\　]/u', '', $field);
        }, $datas['owner']);

        // 车辆信息
        if ($brand = CarBrandAR::find()->select(['name'])->where(['id' => $qualityOrder['car_brand_id']])->scalar()) {
            $qualityOrder['car_brand_type'] = $brand;
        } else {
            $qualityOrder['car_brand_type'] = '未知';
        }

        $datas['car'] = [
            'car_number'  => $qualityOrder['car_number'],
            'car_frame' => $qualityOrder['car_frame'],
            'car_brand_type' => $qualityOrder['car_brand_type'],
            'car_price_range' => $qualityOrder['car_price_range']
        ];
        $datas['car'] = array_map(function ($field) {
            if (!$field) {
                return '无';
            }
            return preg_replace('/[\s\　]/u', '', $field);
        }, $datas['car']);

        // 产品信息
        $datas['items'] = self::getQualityOrderItems($qualityOrder['id'], true);

        // 产品套餐
        $datas['package'] = self::getQualityOrderPackage($datas['items']);

        // 施工信息
        if (!$construct_custom = trim($qualityOrder['construct_unit'])) {
            $construct_custom = '未知';
        }
        $datas['construct'] = [
            'construct_custom' => $construct_custom,
            'price' => $qualityOrder['price'],
            'construct_time' => $qualityOrder['construct_time'],
            'finished_time'  => $qualityOrder['construct_time']
        ];

        $type = 0; // 膜的类型: 0:其他 1-悠耐
        foreach ($datas['items'] as $item) {
            if ($item['brand'] == '悠耐(YONINE)') {
                $type = 1;
            }
        }
        $datas['type'] = $type;
        return $datas;
    }


    /**
     * 获取custom查询质保单信息列表
     * @param $code 质保单号或者管芯号
     */
    public static function getCustomList($code, $custom = false)
    {
        // 管芯号
        if (strpos($code, 'DH') === 0 || strpos($code, 'UN') === 0) {
            if ($ids = QualityOrderItemAR::find()->select(['quality_order_id'])->distinct()->where(['code' => $code])->orderBy('quality_order_id DESC')->column()) {
                $datas = [];
                foreach ($ids as $key => $id) {
                    $datas[] = QualityOrderAR::find()->select(['code', 'owner_name', 'car_brand_id', 'construct_unit', 'construct_time'])->where(['id' => $id])->asArray()->one();
                    $datas[$key]['car_brand'] = CarBrandAR::find()->select(['name'])->where(['id' => $datas[$key]['car_brand_id']])->scalar() ?? '未知';
                    unset($datas[$key]['car_brand_id']);
                    $datas[$key]['construct_time'] = date('Y.m.d', $datas[$key]['construct_time']);
                }
                return $datas;
            } else {
                return false;
            }
        }
        // 质保单号
        elseif (strlen($code) === 13) {
            if ($custom) {
                return false;
            }
            $qualityOrder = QualityOrderAR::find()->where(
                ['code' => $code]
            )->asArray()->one();

            if (empty($qualityOrder)) {
                return false;
            }

            if (mb_strlen($qualityOrder['owner_name']) === 1) {
                $qualityOrder['owner_name'] .= '先生/女士';
            }

            if ($qualityOrder['construct_time']) {
                $qualityOrder['construct_time'] = date('Y.m.d', $qualityOrder['construct_time']);
            } else {
                $qualityOrder['construct_time'] = '无';
            }

            // 质保单信息
            $datas = [];
            $datas['order'] = [
                'quality_order' => $qualityOrder['code'],
                'construct_time' => $qualityOrder['construct_time']
            ];

            // 车主信息
            $datas['owner'] = [
                'owner_name' => $qualityOrder['owner_name']
            ];

            // 车辆信息
            if ($brand = CarBrandAR::find()->select(['name'])->where(['id' => $qualityOrder['car_brand_id']])->scalar()) {
                $qualityOrder['car_brand_type'] = $brand;
            } else {
                $qualityOrder['car_brand_type'] = '未知';
            }
            $datas['car'] = [
                'car_brand_type' => $qualityOrder['car_brand_type'],
            ];
            $datas['car'] = array_map(function ($field) {
                if (!$field) {
                    return '无';
                }
                return preg_replace('/[\s\　]/u', '', $field);
            }, $datas['car']);

            // 产品信息
            $datas['items'] = self::getQualityOrderItems($qualityOrder['id'], true);

            // 套餐信息
            $datas['package'] = self::getQualityOrderPackage($datas['items']);

            // 施工信息
            if (!$construct_custom = trim($qualityOrder['construct_unit'])) {
                $construct_custom = '未知';
            }
            $datas['construct'] = [
                'construct_custom' => $construct_custom,
                'construct_time' => $qualityOrder['construct_time'],
                'finished_time'  => $qualityOrder['construct_time']
            ];

            $type = 0; // 膜的类型: 0:其他 1-悠耐
            foreach ($datas['items'] as $item) {
                if ($item['brand'] == '悠耐(YONINE)') {
                    $type = 1;
                }
            }
            $datas['type'] = $type;
            return $datas;
        } else {
            return false;
        }
    }

    /**
     * 获取质保单产品信息
     * @param integer $orderId 质保单id
     */
    private static function getQualityOrderItems($orderId, $showCode = false)
    {
        $results = [];
        if (Yii::$app->RQ->AR(new QualityOrderItemAR())->exists([
            'where' => ['quality_order_id' => $orderId]
        ])) {
            $items = Handler::getMultiAttributes(new QualityOrder(['id' => $orderId]), ['items']);
            foreach ($items['items'] as $key => $item) {
                if ($showCode) {
                    $results[$key]['code'] = trim($item['code']);
                }
                $results[$key]['brand'] = self::getMembraneBrand(trim($item['code']));
                $results[$key]['type'] = self::getMumbraneType(trim($item['code']));

                if (empty($item['place_name'])) {
                    unset($results[$key]);
                    continue;
                }
                $results[$key]['place'] = $item['place_name'];
                $results[$key]['work_option'] = '';
                if ($results[$key]['brand'] == '悠耐(YONINE)') {
                    if (empty($item['work_option'])) {
                        $results[$key]['place'] = '整车';
                        $results[$key]['work_option'] = '整车';
                    } else {
                        $results[$key]['place'] = '局部';
                        $results[$key]['work_option'] = $item['work_option'];

                    }
                }
                $results[$key]['amount'] = 1; // 默认为1
                $results[$key]['sales'] = !empty($item['sales']) ? $item['sales'] : '无';

                if ($key === 0 && empty($item['technician'])) {
                    unset($results[$key]);
                    continue;
                } elseif ($key > 0 && empty($item['technician'])) {
                    $results[$key]['technician'] = $results[0]['technician'];
                } else {
                    $results[$key]['technician'] = $item['technician'];
                }
            }
        }
        return $results;
    }

    /**
     * 获取膜的品牌
     * @param string $itemCode 质保单产品序列号
     */
    private static function getMembraneBrand($itemCode)
    {
        if (strpos($itemCode, 'DHTY') === 0) {
            return '天御';
        } elseif (strpos($itemCode, 'DH') === 0) {
            return '欧帕斯';
        } elseif (strpos($itemCode, 'UN') === 0) {
            return '悠耐(YONINE)';
        }
        return '未知';
    }

    /**
     * 获取膜的类型
     * @param string $itemCode 质保单产品序列号
     */
    private static function getMumbraneType($itemCode)
    {
        if (strpos($itemCode, 'DHTY') === 0) {
            if (strpos($itemCode, 'Z8') === 4) {
                return 'Z8';
            } elseif (strpos($itemCode, 'G30') === 4) {
                return 'G30';
            } elseif (strpos($itemCode, 'G20') === 4) {
                return 'G20';
            } elseif (strpos($itemCode, 'G15') === 4) {
                return 'G15';
            } else {
                return '未知';
            }
        } elseif (strpos($itemCode, 'DH') === 0) {
            if (strpos($itemCode, 'U9') === 2) {
                return 'U9';
            } elseif (strpos($itemCode, 'U7') === 2) {
                return 'U7';
            } elseif (strpos($itemCode, 'I8') === 2) {
                return 'I8';
            } elseif (strpos($itemCode, 'R5') === 2) {
                return 'R5';
            } elseif (strpos($itemCode, 'V5') === 2) {
                return 'V5';
            } elseif (strpos($itemCode, 'E5') === 2) {
                return 'E5';
            } else {
                return '未知';
            }
        } elseif (strpos($itemCode, 'UN') === 0) {
            return '漆面保护膜';
        }
        return '未知';
    }

    /**
     * 获取质保单套餐
     * @param array $items
     */
    private static function getQualityOrderPackage($items)
    {
        $package = '非套餐施工';
        $frontType = '';
        $otherType = '';
        foreach ($items as $key => $item) {
            if ($item['type'] == '漆面保护膜') {
                return '悠耐禄系列';
            }
            if ($key == 0 && $item['place'] == '前挡') {
                $frontType = $item['type'];
            }
            if ($key == 1 && $item['place'] != '前挡') {
                $otherType = $item['type'];
            }
            if ($key > 1 && $item['type'] != $items[$key - 1]['type']) {
                return $package;
            }
        }

        if (empty($frontType) || empty($otherType)) {
            return $package;
        }

        if ($frontType == 'U9') {
            switch ($otherType) {
                case 'U7':
                    $package = '吉祥套餐';
                    break;
                case 'I8':
                    $package = '如意套餐';
                    break;
                case 'R5':
                    $package = '幸福套餐';
                    break;
                case 'V5':
                    $package = '平安套餐';
                    break;
                case 'E5':
                    $package = '开心套餐';
                    break;
                default:
                    $package = '非套餐施工';
            }
        } elseif ($frontType == 'Z8') {
            switch ($otherType) {
                case 'G15':
                    $package = 'Z8+G15套餐';
                    break;
                case 'G20':
                    $package = 'Z8+G20套餐';
                    break;
                case 'G30':
                    $package = 'Z8+G30套餐';
                    break;
                default:
                    $package = '非套餐施工';
            }
        } else {
            $package = '非套餐施工';
        }
        return $package;
    }

    /**
     * @param $itemCode 卷芯号
     */
    public static function getQualityOrderPackageId($itemCode)
    {
        if (strpos($itemCode, 'DHTY') === 0) {
            if (strpos($itemCode, 'Z8') === 4) {
                $name = 'Z8(天御)';
            } elseif (strpos($itemCode, 'G15') === 4) {
                $name = 'G15(天御)';
            } elseif (strpos($itemCode, 'G20') === 4) {
                $name = 'G20(天御)';
            } elseif (strpos($itemCode, 'G30') === 4) {
                $name = 'G30(天御)';
            } else {
                return 0;
            }
        } elseif (strpos($itemCode, 'DH') === 0) {
            if (strpos($itemCode, 'U9') === 2) {
                $name = 'U9(APEX)';
            } elseif (strpos($itemCode, 'E5') === 2) {
                $name = 'E5(APEX)';
            } elseif (strpos($itemCode, 'V5') === 2) {
                $name = 'V5(APEX)';
            } elseif (strpos($itemCode, 'R5') === 2) {
                $name = 'R5(APEX)';
            } elseif (strpos($itemCode, 'U7') === 2) {
                $name = 'U7(APEX)';
            } elseif (strpos($itemCode, 'I8') === 2) {
                $name = 'I8(APEX)';
            } else {
                return 0;
            }
        } elseif (strpos($itemCode, 'UN') === 0) {
            $name = 'UN(YONINE)';
        } else {
            return 0;
        }
        return QualityPackageAR::find(['id'])->where(['name' => $name])->scalar();
    }

}
