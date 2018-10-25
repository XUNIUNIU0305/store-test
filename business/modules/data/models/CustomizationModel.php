<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-30
 * Time: 上午10:24
 */

namespace business\modules\data\models;


use business\models\handler\BusinessAreaHandler;
use business\models\handler\CustomUserHandler;
use business\models\handler\MembraneOrderHandler;
use business\models\handler\OrderHandler;
use business\models\parts\Area;
use business\modules\data\models\traits\AutoSplitDateTrait;
use business\modules\data\models\traits\UserAreaTrait;
use common\ActiveRecord\ProductBigImagesAR;
use common\models\Model;
use common\models\parts\custom\CustomUser;
use common\models\parts\Order;
use common\models\parts\Product;

class CustomizationModel extends Model
{
    const SCE_SEARCH = 'search';
    const SCE_PRODUCT_SEARCH = 'product_search';

    public $level = Area::LEVEL_TOP;
    public $user_level;
    public $start;
    public $end;
    public $by = 'day';
    public $id;

    use AutoSplitDateTrait,
        UserAreaTrait;

    public function init()
    {
        try{
            $this->start = $this->start ? date('Y-m-d H:i:s', strtotime($this->start)) : date('Y-m-d 00:00:00', strtotime('-1 day'));
            $this->end = $this->end ? date('Y-m-d H:i:s', strtotime($this->end)) : date('Y-m-d 23:59:59', strtotime('-1 day'));
        } catch (\Exception $e){
            $this->addError('date', 13381);
        }
    }

    public function scenarios()
    {
        return [
            self::SCE_SEARCH => [
                'level',
                'user_level',
                'start',
                'end',
                'by'
            ],
            self::SCE_PRODUCT_SEARCH => [
                'level',
                'user_level',
                'start',
                'end',
                'by',
                'id'
            ]
        ];
    }

    public function rules()
    {
        return [
            [
                ['user_level'],
                'each',
                'rule' => [
                    'in',
                    'range' => CustomUser::getLevels()
                ],
                'message' => 9002
            ],
            [
                ['level'],
                'in',
                'range' => Area::$levels,
                'message' => 9002
            ],
            [
                ['id'],
                'required',
                'message' => 9001
            ],
            [
                ['id'],
                'integer',
                'message' => 9002
            ],
            [
                ['start', 'end'],
                'date',
                'format' => 'php:Y-m-d H:i:s',
                'message' => 9002
            ],
            [
                ['by'],
                'in',
                'range' => ['hour', 'day', 'week', 'month']
            ]
        ];
    }

    /**
     * 数据搜索
     * @return array|bool
     */
    public function search()
    {
        try {
            $dateItems = $this->autoSplitDate();
            $uid = $this->getUserId();
            $items = [];
            foreach ($dateItems as $key=>$date){
                if(!isset($dateItems[$key+1])) break;
                $start = $date['date'];
                $end = $dateItems[$key+1]['date'];
                $customizationTotal = OrderHandler::queryCustomizationTotalFeeBy($uid, $start, $end);
                $customizationTotal += MembraneOrderHandler::queryTotalFeeBy($uid, $start, $end);
                $total = OrderHandler::queryNormalTotalFeeBy($uid, $start, $end);
                $items[] = compact('date', 'total', 'customizationTotal');
            }
            //前12产品
            $products = OrderHandler::queryTopNormalProductBy($uid, $this->start, $this->end, Order::CUSTOM_STATUS_NO, 12);
            $isProducts = OrderHandler::queryTopNormalProductBy($uid, $this->start, $this->end, Order::CUSTOM_STATUS_IS, 12);
            return [
                'items' => $items,
                'products' => $this->queryProduct($products),
                'isProducts' => $this->queryProduct($isProducts)
            ];
        } catch (\Exception $e){
            $this->addError('search', 13380);
            return false;
        }
    }

    private function queryProduct($products)
    {
        $res = [];
        $host = \Yii::$app->params['API_Hostname'];
        foreach ($products as $product){
            $id = $product['product_id'];
            $productObj = new Product(['id' => $id]);
            $image = ProductBigImagesAR::find()->select(['filename'])
                ->where(['product_id' => $id])->limit(1)->scalar();
            $res[] = [
                'id' => $id,
                'total' => $product['total'],
                'title' => $productObj->getTitle(),
                'price' => $productObj->getPrice(),
                'img' => $host . '/' . $image
            ];

        }
        return $res;
    }

    /**
     * 产品搜索
     * @return bool|array
     */
    public function productSearch()
    {
        try{
            $mainArea = $this->getMainArea();
            if($this->level >= $mainArea->level){
                //获取同级/下级
                $areaItems = BusinessAreaHandler::findAreaByLevel($mainArea, $this->level);
            } else {
                //获取上级
                $areaItems = BusinessAreaHandler::findParentLevel($mainArea, $this->level);
            }
            $res = [];
            $nTotal = 0;
            foreach ($areaItems as $item){
                $fif = BusinessAreaHandler::findAreaByLevel($item, Area::LEVEL_FIFTH);
                $uid = CustomUserHandler::findUserIdBy(array_column($fif, 'id'));
                $total = OrderHandler::queryTotalFeeByProduct($uid, $this->start, $this->end, $this->id);
                $res[] = $item + ['total' => $total];
                $nTotal += $total;
            }
            return [
                'items' => $res,
                'total' => $nTotal
            ];
        } catch (\Exception $e) {
            $this->addError('', 13380);
            return false;
        }
    }
}