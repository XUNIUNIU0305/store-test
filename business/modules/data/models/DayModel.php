<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-21
 * Time: 上午11:24
 */

namespace business\modules\data\models;

use business\components\BusinessUser;
use business\models\handler\BusinessAreaHandler;
use business\models\handler\CustomUserHandler;
use business\models\handler\MembraneOrderHandler;
use business\models\handler\OrderHandler;
use business\models\parts\Account;
use business\models\parts\Area;
use business\models\parts\Role;
use business\modules\data\models\traits\BusinessAreaTrait;
use business\modules\data\models\traits\UserAreaTrait;
use common\ActiveRecord\BusinessAreaAR;
use common\ActiveRecord\BusinessAreaConsumptionStatisticsAR;
use common\ActiveRecord\BusinessRoleAR;
use common\ActiveRecord\BusinessUserAR;
use common\ActiveRecord\CustomConsumptionStatisticsAR;
use common\ActiveRecord\CustomUserAddressAR;
use common\ActiveRecord\CustomUserAR;
use common\ActiveRecord\CustomUserAuthorizationAR;
use common\ActiveRecord\CustomUserRegistercodeAR;
use common\ActiveRecord\MembraneOrderItemAR;
use common\ActiveRecord\MembraneProductParamsAR;
use common\ActiveRecord\OrderCustomRecordAR;
use common\ActiveRecord\OrderItemAR;
use common\ActiveRecord\OrderProductRecordAR;
use common\ActiveRecord\ProductAR;
use common\ActiveRecord\ProductBigImagesAR;
use common\ActiveRecord\ProductSKUAR;
use common\models\Model;
use common\models\parts\Address;
use common\models\parts\MembraneProduct;
use common\models\parts\partner\Authorization;
use common\models\parts\Product;
use common\models\parts\supply\SupplyUser;
use yii\data\Pagination;
use Yii;

class DayModel extends Model
{
    const SCE_TOTAL_PREVIEW = 'total_preview';
    const SCE_DAY_TOP_PRODUCT = 'day_top_product';
    const SCE_DAY_TOP_PRICE = 'day_top_price';
    const SCE_DAY_TOP_BRAND = 'day_top_brand';
    const SCE_DAY_AREA = 'day_area';
    const SCE_DAY_STORE = 'day_store';
    const SCE_DAY_CUSTOM_CONSUMPTION = 'day_custom_consumption';
    const SCE_DAY_AREA_CONSUMPTION = 'day_area_consumption';
    const SCE_DAY_REGISTER = 'day_register';

    public $level = Area::LEVEL_TOP;
    public $sort = 1;

    public $show;
    public $parent_area_id;
    public $current_page;
    public $page_size;
    public $date;

    use UserAreaTrait,
        BusinessAreaTrait;

    protected $start;
    protected $end;

    public function init()
    {
        $this->start = date('Y-m-d', strtotime('-1 day'));
        $this->end = $this->start . ' 23:59:59';
    }

    public function scenarios()
    {
        return [
            self::SCE_TOTAL_PREVIEW => [],
            self::SCE_DAY_TOP_PRODUCT => [],
            self::SCE_DAY_TOP_PRICE => [],
            self::SCE_DAY_TOP_BRAND => [],
            self::SCE_DAY_AREA => ['level'],
            self::SCE_DAY_STORE => ['sort'],
            self::SCE_DAY_CUSTOM_CONSUMPTION => ['show'],
            self::SCE_DAY_AREA_CONSUMPTION => ['parent_area_id', 'current_page', 'page_size'],
            self::SCE_DAY_REGISTER => [
                'parent_area_id',
                'date',
            ],
        ];
    }

    public function rules()
    {
        return [
            [
                ['level'],
                'in',
                'range' => [Area::LEVEL_TOP, Area::LEVEL_SECONDARY, Area::LEVEL_TERTIARY, Area::LEVEL_QUATERNARY, Area::LEVEL_FIFTH],
                'message' => 9002
            ],
            [
                ['sort'],
                'in',
                'range' => [1, -1],
                'message' => 9002
            ],
            [
                ['parent_area_id'],
                'default',
                'value' => Yii::$app->BusinessUser->account->area->id,
            ],
            [
                ['show'],
                'default',
                'value' => 10,
            ],
            [
                ['current_page', 'page_size'],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
            [
                ['show'],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
            [
                ['date'],
                'default',
                'value' => date('Y-m-d'),
            ],
            [
                ['date'],
                'date',
                'format' => 'Y-m-d',
                'message' => 9002,
            ],
            [['show'], 'required', 'message' => 9001],
            [['parent_area_id', 'current_page', 'page_size', 'date'], 'required', 'message' => 9001],
        ];
    }

    /**
     * 总转化率
     */
    public function totalPreview()
    {
        try{
            $uid = $this->getUserId();
            //总消费人数
            $totalNum = count(array_unique(array_merge(OrderHandler::queryOrderUid($uid), MembraneOrderHandler::queryOrderUid($uid))));

            //实控门店
            $customNum = count($uid);
            //总转化率
            $totalConversionRate = round($totalNum / $customNum * 100);
            //当日订单
            $order = $this->getOrder();
            $membraneOrder = $this->getMembraneOrder();
            //日消费人数
            $dayFeeNum = count(array_unique(array_merge(array_column($order, 'custom_user_id'), array_column($membraneOrder, 'custom_user_id'))));
            //日活跃度
            $dayActivity = round($dayFeeNum / $customNum * 100);
            //日总金额
            $dayTotalFee = array_sum(array_column($order, 'total_fee')) + array_sum(array_column($membraneOrder, 'total_fee'));
            //日订单数
            $dayOrderNum = count($order) + count($membraneOrder);
            //客单价
            $unitPrice = $dayFeeNum ? round($dayTotalFee / $dayFeeNum) : 0;
            //客单量
            $unitNum = $dayFeeNum ? round($dayOrderNum / $dayFeeNum) : 0;
            //昨日新增注册用户
            $accounts = $this->getAccountId();
            $registerNum = count($accounts['registerUser']);
            $codeNum = count($accounts['codeUser']);
            return [
                'totalConversionRate' => $totalConversionRate,
                'dayActivity' => $dayActivity,
                'dayTotalFee' => intval($dayTotalFee),
                'dayOrderNum' => $dayOrderNum,
                'dayFeeNum' => $dayFeeNum,
                'customNum' => $customNum,
                'unitPrice' => $unitPrice,
                'unitNum' => $unitNum,
                'registerNum' => $registerNum,
                'codeNum' => $codeNum,
                'totalNum' => $totalNum
            ];
        } catch (\Exception $e){
            $this->addError('total', $e->getCode());
            return false;
        }
    }

    private $accountId = false;

    /**
     * 按区域id查找昨天注册用户／邀请用户
     * @return array|bool
     */
    private function getAccountId()
    {
        if($this->accountId === false){
            $codeUser = [];
            $users = CustomUserHandler::findUserIdBy($this->getFifthAreaId());
            $registerUser = CustomUserAuthorizationAR::find()->alias('a')
                ->select(['custom_user_account'])
                ->where(['>', 'a.account_valid_datetime', $this->start])
                ->andWhere(['<', 'a.account_valid_datetime', $this->end])
                ->leftJoin(CustomUserAR::tableName() . ' b', 'a.custom_user_id = b.id')
                ->andWhere(['a.status' => Authorization::STATUS_ACCOUNT_VALID])
                ->andWhere(['b.business_area_id' => array_column($users, 'id')])
                ->column();

            if(count($registerUser)){
                $codeUser = CustomUserRegistercodeAR::find()
                    ->select(['account'])
                    ->where(['account' => $registerUser])
                    ->andWhere(['used' => 1])
                    ->column();
            }
            $this->accountId = compact('registerUser', 'codeUser');
        }

        return $this->accountId;
    }

    private $order = false;

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    private function getOrder()
    {
        if($this->order === false){
            $this->order = OrderHandler::queryActiveOrderBy($this->getUserId(), $this->start, $this->end);
        }
        return $this->order;
    }

    private $membraneOrder = false;

    /**
     * @return array|bool|\yii\db\ActiveRecord[]
     */
    private function getMembraneOrder()
    {
        if($this->membraneOrder === false){
            $this->membraneOrder = MembraneOrderHandler::queryActiveOrderBy($this->getUserId(), $this->start, $this->end);
        }
        return $this->membraneOrder;
    }

    /**
     * 热销单品
     * @return array|bool
     */
    public function dayTopProduct()
    {
        try{
            $order = $this->getOrder();
            $membraneOrder = $this->getMembraneOrder();

            $max = OrderItemAR::find()
                ->alias('a')
                ->select(['sum(a.count) total', 'b.product_id id'])
                ->where(['order_id' => array_column($order, 'id')])
                ->leftJoin(ProductSKUAR::tableName() . ' b', 'a.product_sku_id = b.id')
                ->groupBy('b.product_id')
                ->orderBy(['total'=>SORT_DESC])
                ->limit(1)
                ->asArray()->one();

            $maxM = MembraneOrderItemAR::find()
                ->select(['sum(1) total', 'membrane_product_id id'])
                ->where(['membrane_order_id' => array_column($membraneOrder, 'id')])
                ->groupBy('membrane_product_id')
                ->orderBy(['total' => SORT_DESC])
                ->limit(1)
                ->asArray()->one();

            if(!$max && !$maxM)
                return [];

            if($max['total'] > $maxM['total']){
                return $this->renderOrder($max, $order);
            } else {
                return $this->renderMembraneOrder($maxM, $membraneOrder);
            }

        } catch (\Exception $e){
            $this->addError('', 13380);
            return false;
        }
    }

    /**
     * 输出订单
     * @param $max
     * @param $order
     * @return array
     */
    private function renderOrder($max, $order)
    {
        extract($max);
        /** @var int $id */
        /** @var int $total */
        $product = ProductAR::findOne($id);
        $skuItems = ProductSKUAR::find()->where(['product_id' => $id])->indexBy('id')->all();
        $items = OrderItemAR::find()->where(['order_id' => array_column($order, 'id')])
            ->select(['count', 'product_sku_id id', 'sku_attributes'])
            ->andWhere(['product_sku_id' => array_keys($skuItems)])
            ->asArray()->all();
        $newItems = [];
        foreach ($items as $item){
            $key = $item['id'];
            if(isset($newItems[$key])){
                $newItems[$key]['total'] += $item['count'];
            } else {
                $attributes = unserialize($item['sku_attributes']);
                $attr = [];
                foreach ($attributes as $attribute){
                    $attr[] = $attribute['option'];
                }
                $newItems[$key] = [
                    'total' => $item['count'],
                    'attributes' => implode(' ', array_map(function($item){
                        return $item['option'];
                    }, unserialize($item['sku_attributes'])))
                ];
            }
        }

        $image = ProductBigImagesAR::findOne($product->product_big_images_id);
        $host = \Yii::$app->params['OSS_PostHost'];
        return [
            'product' => [
                'title' => $product->title,
                'image' => $host . '/' . $image->filename,
                'price' => [
                    'min' => $product->min_price,
                    'max' => $product->max_price
                ],
                'total' => $total
            ],
            'items' => array_values($newItems)
        ];
    }

    /**
     * 输出膜订单
     * @param $max
     * @param $order
     * @return array
     */
    private function renderMembraneOrder($max, $order)
    {
        extract($max);
        /** @var int $id */
        /** @var int $total */
        $product = new MembraneProduct(['id' => $id]);
        $params = MembraneProductParamsAR::find()
            ->where(['product_id' => $id])
            ->indexBy('id')
            ->orderBy(['price' => SORT_ASC])
            ->all();
        $items = MembraneOrderItemAR::find()->where(['membrane_order_id' => array_column($order, 'id')])
            ->andWhere(['membrane_product_params_id' => array_column($params, 'id')])
            ->select(['sum(1) total', 'membrane_product_params_id id'])
            ->groupBy('membrane_product_params_id')
            ->orderBy(['total' => SORT_DESC])
            ->asArray()->all();

        $newItems = [];
        foreach ($items as $item){
            $param = $params[$item['id']];
            $newItems[] = [
                'attributes' => $param->name,
                'total' => $item['total']
            ];
        }

        $min = current($params)['price'] ?? 0;
        end($params);
        $max = current($params)['price'] ?? 0;

        return [
            'product' => [
                'title' => $product->getName(),
                'image' => '/images/membrane/product/item.png',
                'price' => [
                    'min' => $min,
                    'max' => $max
                ],
                'total' => $total
            ],
            'items' => $newItems
        ];
    }

    /**
     * 销售冠军
     */
    public function dayTopPrice()
    {
        try{
            $order = $this->getOrder();
            $membraneOrder = $this->getMembraneOrder();

            //销售饿最高产品id
            $max = OrderItemAR::find()->alias('a')
                ->select(['sum(total_fee) total', 'b.product_id id'])
                ->where(['order_id' => array_column($order, 'id')])
                ->leftJoin(ProductSKUAR::tableName() . ' b', 'a.product_sku_id = b.id')
                ->groupBy('b.product_id')
                ->orderBy(['total' => SORT_DESC])
                ->limit(1)
                ->asArray()->one();

            $maxm = MembraneOrderItemAR::find()
                ->select(['sum(price) total', 'membrane_product_id id'])
                ->where(['membrane_order_id' => array_column($membraneOrder, 'id')])
                ->groupBy('membrane_product_id')
                ->orderBy(['total' => SORT_DESC])
                ->limit(1)
                ->asArray()->one();

            if(!$max && !$maxm)
                return [];

            if($max['total'] > $maxm['total']){
                return $this->renderOrder($max, $order);
            } else {
                return $this->renderMembraneOrder($maxm, $membraneOrder);
            }

        } catch (\Exception $e){
            $this->addError('', 13380);
            return false;
        }
    }

    /**
     * 喜爱品牌
     */
    public function dayTopBrand()
    {
        try{
            if(!$hot = OrderHandler::queryHotSupply($this->getUserId(), $this->start, $this->end))
                return [];
            $supply = new SupplyUser(['id' => $hot['id']]);

            $items = OrderHandler::queryTopItemBySupply($supply->id, 3);
            $supply = [
                'name' => $supply->getBrandName(),
                'image' => $supply->getHeaderImg()
            ];

            $products = ProductAR::find()->select(['min_price', 'max_price', 'id', 'title', 'product_big_images_id image'])
                ->where(['id' => array_column($items, 'id')])
                ->indexBy('id')
                ->asArray()->all();

            $images = ProductBigImagesAR::find()->select(['id', 'filename'])
                ->where(['id' => array_column($products, 'image')])
                ->indexBy('id')
                ->asArray()->all();

            $host = \Yii::$app->params['OSS_PostHost'];
            foreach ($products as $key=>$product){
                $product['image'] = $host . '/'. $images[$product['image']]['filename'];
                $products[$key] = $product;
            }
            foreach ($items as $key=>$item){
                $item = array_merge($item, $products[$item['id']]);
                $items[$key] = $item;
            }
            return compact('items', 'supply');

        } catch (\Exception $e){
            $this->addError('hot-brand', 13380);
            return false;
        }
    }

    /**
     * 区域排名
     * @return array|bool
     */
    public function dayArea()
    {
        try{
            $mainArea = $this->getMainArea();
            if($mainArea->level <= $this->level){
                $items = BusinessAreaHandler::findAreaByLevel($mainArea, $this->level);
            } else {
                $items = BusinessAreaHandler::findParentLevel($mainArea, $this->level);
            }
            foreach ($items as $key=>$item){
                $obj = BusinessAreaHandler::findAreaByLevel($item, Area::LEVEL_FIFTH);
                $uid = CustomUserHandler::findUserIdBy(array_column($obj, 'id'));
                //账户总数
                $totalAccount = count($uid);
                //日订单
                $order = OrderHandler::queryActiveOrderBy($uid, $this->start, $this->end);
                $membraneOrder = MembraneOrderHandler::queryActiveOrderBy($uid, $this->start, $this->end);
                $totalFeeAccount = count(array_unique(array_merge(OrderHandler::queryOrderUid($uid), MembraneOrderHandler::queryOrderUid($uid))));
                //销售额
                $soles = intval(array_sum(array_column($order, 'total_fee')) + array_sum(array_column($membraneOrder, 'total_fee')));
                //客单价
                $unitPrice = $totalFeeAccount ? round($soles / $totalFeeAccount) : 0;
                //单日消费账户
                $dayFeeAccount = count(array_unique(array_merge(array_column($order, 'custom_user_id'), array_column($membraneOrder, 'custom_user_id'))));
                $items[$key] = [
                    'area' => [
                        'id' => $item['id'],
                        'name' => $item['name'],
                        'level' => $item['level']
                    ],
                    'soles' => $soles,
                    'unitPrice' => $unitPrice,
                    'totalAccount' => $totalAccount,
                    'dayFeeAccount' => $dayFeeAccount,
                    'totalFeeAccount' => $totalFeeAccount
                ];
            }

            usort($items, function($a, $b){
                return $a['soles'] < $b['soles'] ? 1 : -1;
            });
            $items = array_slice($items, 0, 5);

            return compact('items');
        } catch (\Exception $e){
            $this->addError('', 13380);
            return false;
        }
    }

    /**
     * 门店排名
     * @return array|bool
     */
    public function dayStore()
    {
        try{
            $users = CustomUserHandler::findUserBy($this->getFifthAreaId());
            $address = CustomUserAddressAR::find()
                ->select(['id', 'custom_user_id'])
                ->where(['custom_user_id' => array_column($users, 'id')])
                ->andWhere(['default' => CustomUserAddressAR::DEFAULT_ADDRESS])
                ->indexBy('custom_user_id')
                ->asArray()
                ->column();
            $order = $this->getOrder();
            $membraneOrder = $this->getMembraneOrder();
            $res = [];
            foreach ($users as $user){
                $total = 0;
                foreach ($order as $key=>$item){
                    if($item->custom_user_id == $user['id']){
                        $total += $item->total_fee;
                        unset($order[$key]);
                    }
                }
                foreach ($membraneOrder as $key=>$item){
                    if($item->custom_user_id == $user['id']){
                        $total += $item->total_fee;
                        unset($order[$key]);
                    }
                }
                $obj = [
                    'id' => $user['id'],
                    'account' => $user['account'],
                    'mobile' => $user['mobile']
                ];
                if(isset($address[$user['id']])){
                    $add = new Address(['id' => $address[$user['id']]]);
                    $obj = array_merge($obj, [
                        'receiver' => $add->getConsignee(),
                        'address' => $add->getDetail(),
                        'area' => $add->getProvince(true). ' ' . $add->getCity(true) . ' ' . $add->getDistrict(true)
                    ]);
                }
                $res[] = [
                    'user' => $obj,
                    'total' => $total
                ];
            }
            usort($res, function($a, $b){
                if($a['total'] == $b['total'])
                    return 0;
                if($this->sort > 0){
                    return $a['total'] < $b['total'] ? 1 : -1;
                } else {
                    return $b['total'] < $a['total'] ? 1 : -1;
                }
            });
            return array_slice($res, 0, 10);
        } catch (\Exception $e){
            $this->addError('store', 13380);
            return false;
        }
    }

    /*
     * 日门店消费排名
     */
    public function dayCustomConsumption()
    {
        if (empty($this->show)) {
            $this->addError('dayCustomConsumption', 9001);
            return false;
        }

        // 1.确定用户的身份
        $userInfo = BusinessUserAR::find()
            ->select(['level', 'business_role_id', 'business_area_id'])
            ->where(['id' => Yii::$app->user->id, 'status' => Account::STATUS_NORMAL])
            ->asArray()
            ->one();

        // 2.确定用户所属的地区, 0是超管
        // $userInfo['business_area_id'];

        // 3.确定该地区下的所有订单// 子地区，子子地区(根据角色)
        // var_dump((new Area(['id' => $businessAreaId]))->getChildren());

        // 4.判断用户角色
        switch ($userInfo['business_role_id']) {
            case 1:
                break;
            case 2:
            case 3:
                $areaIdName = 'business_top_area_id';
                break;
            case 4:
                $areaIdName = 'business_secondary_area_id';
                break;
            case 5:
                $areaIdName = 'business_tertiary_area_id';
                break;
            case 6:
                $areaIdName = 'business_quaternary_area_id';
                break;
            case 7:
            case 8:
                $areaIdName = 'business_area_id';
                break;
            default:
                $this->addError('dayCustomConsumption', 13081);
                return false;
                break;
        }

        if ($userInfo['business_role_id'] == 1) {
            $records = OrderProductRecordAR::find()
                                ->select(['product_id', 'total_fee' => 'SUM(`total_fee`)'])
                                ->limit($this->show)
                                ->where(['>=', 'create_unixtime', strtotime(date('Y-m-d') . ' 00:00:00')])
                                ->andWhere(['<', 'create_unixtime', strtotime(date('Y-m-d') . ' 23:59:59')])
                                ->groupBy(['product_id'])
                                ->orderBy('total_fee DESC')
                                ->asArray()
                                ->all();
        } else {
            $records = OrderProductRecordAR::find()
                ->select(['product_id', 'total_fee' => 'SUM(`total_fee`)'])
                ->where(['>=', 'create_unixtime', strtotime(date('Y-m-d') . ' 00:00:00')])
                ->andWhere(['<', 'create_unixtime', strtotime(date('Y-m-d') . ' 23:59:59')])
                ->andWhere([$areaIdName => $userInfo['business_area_id']])
                ->limit($this->show)
                ->groupBy(['product_id'])
                ->orderBy('total_fee DESC')
                ->asArray()
                ->all();
        }

        $datas = [];
        // 5.取出商品的 title, filename, 销售额 total_fee, 记录表id
        foreach ($records as $record) {
            $datas[] = CustomUserHandler::getMultiAttributes($record, [
                'title' => 'product_id',
                'filename' => 'product_id',
                'total_fee',
                '_func' => [
                    'product_id' => function($productId, $aliasName){
                        static $product = null;
                        if(is_null($product)){
                            $product = new Product([
                                'id' => $productId,
                            ]);
                        }else{
                            if($product->id != $productId){
                                $product = new Product([
                                    'id' => $productId,
                                ]);
                            }
                        }
                        if($aliasName == 'title'){
                            return $product->title;
                        }elseif($aliasName == 'filename'){
                            return $product->mainImage->path;
                        }else{
                            return '';
                        }
                    },
                ],
            ]);
        }

        return $datas;
    }

    /*
     * 日地区消费排名
     */
    public function dayAreaConsumption()
    {
        if (!isset($this->parent_area_id) || !isset($this->current_page) || !isset($this->page_size)) {
            $this->addError('dayCustomConsumption', 9001);
            return false;
        }
        if(Yii::$app->BusinessUser->account->area->level->level != Area::LEVEL_UNDEFINED && Yii::$app->BusinessUser->account->role->id != Role::SUPER_ADMIN){
            if ((new Account(['id' => Yii::$app->user->id]))->getTopArea()->id != (new Area(['id' => $this->parent_area_id]))->getTopArea()->id) {
                $this->addError('dayCustomConsumption', 13081);
                return false;
            }
        }

        // 获得 parent_area_id 的所以子 id 和 level
        $areaIds = Yii::$app->RQ->AR(new BusinessAreaAR)->column([
            'select' => ['id'],
            'where' => [
                'parent_business_area_id' => $this->parent_area_id,
                'display' => Area::DISPLAY_ON,
            ],
            'orderBy' => ['id' => SORT_ASC],
        ]);

        $provider = new \yii\data\ActiveDataProvider([
            'query' => BusinessAreaConsumptionStatisticsAR::find()
            ->select(['id', 'area_id', 'area_level', 'daily_custom_consumption_count', 'daily_consumption_amount', 'daily_custom_unconsumption_count'])
            ->where(['area_id' => $areaIds])
            ->asArray(),
            'pagination' => [
                'page' => $this->current_page - 1,
                'pageSize' => $this->page_size,
            ],
            'sort' => [
                'defaultOrder' => [
                    'daily_consumption_amount' => SORT_DESC,
                    'id' => SORT_ASC,
                ],
            ],
        ]);

        $datas = [];
        foreach ($provider->models as $tempData) {
            $tempData['area_name'] = (new Area(['id' => $tempData['area_id']]))->name;
            $datas['areas_consumption']['data'][] = $tempData;
        }
        $datas['areas_consumption']['count'] = $provider->count;
        $datas['areas_consumption']['total_count'] = $provider->totalCount;
        $parentArea = new Area([
            'id' => $this->parent_area_id,
        ]);
        $datas['parent_area_info'] = [
            'area_id' => $parentArea->id,
            'area_name' => $parentArea->name,
            'area_level' => $parentArea->level->level,
        ];
        return $datas;
    }

    public function dayRegister(){
        if(Yii::$app->BusinessUser->account->area->level->level != Area::LEVEL_UNDEFINED){
            if ((new Account(['id' => Yii::$app->user->id]))->getTopArea()->id != (new Area(['id' => $this->parent_area_id]))->getTopArea()->id) {
                $this->addError('dayRegister', 13081);
                return false;
            }
        }
        switch(($parentArea = new Area(['id' => $this->parent_area_id]))->level->level){
            case Area::LEVEL_UNDEFINED:
                $parentAreaField = 'business_top_area_id';
                $presentAreaField = 'business_top_area_id';
                break;

            case Area::LEVEL_TOP:
                $parentAreaField = 'business_top_area_id';
                $presentAreaField = 'business_secondary_area_id';
                break;

            case Area::LEVEL_SECONDARY:
                $parentAreaField = 'business_secondary_area_id';
                $presentAreaField = 'business_tertiary_area_id';
                break;

            case Area::LEVEL_TERTIARY:
                $parentAreaField = 'business_tertiary_area_id';
                $presentAreaField = 'business_quaternary_area_id';
                break;

            case Area::LEVEL_QUATERNARY:
                $parentAreaField = 'business_quaternary_area_id';
                $presentAreaField = 'business_area_id';
                break;

            case Area::LEVEL_FIFTH:
                $parentAreaField = 'business_area_id';
                $presentAreaField = 'business_area_id';
                break;

            default:
                $this->addError('dayRegister', 13081);
                return false;
        }
        $provider = new \yii\data\ActiveDataProvider([
            'query' => \common\ActiveRecord\CustomUserRegistercodeAR::find()
                        ->select(['id' => $presentAreaField, 'quantity' => "count(*)"])
                        ->where(['>=', 'register_time', $this->date . ' 00:00:00'])
                        ->andWhere(['<', 'register_time', $this->date . ' 23:59:59'])
                        ->andFilterWhere([$parentAreaField => $parentArea->id ? : null])
                        ->groupBy([$presentAreaField])
                        ->orderBy(['quantity' => SORT_DESC])
                        ->asArray()
            ,
        ]);

        return [
            'parent_area_info' => [
                'area_id' => $parentArea->id,
                'area_name' => $parentArea->name,
                'area_level' => $parentArea->level->level,
            ],
            'present_area_info' => CustomUserHandler::getMultiAttributes($provider, [
                'data' => 'models',
                '_func' => [
                    'models' => function($models)use($parentArea){
                        $result = array_map(function($model){
                            $area = new Area([
                                'id' => $model['id'],
                            ]);
                            return [
                                'area_id' => $area->id,
                                'area_name' => $area->name,
                                'area_level' => $area->level->level, 
                                'quantity' => $model['quantity'],
                            ];
                        }, $models);
                        $hasRegisterList = [];
                        foreach($result as $area){
                            $hasRegisterList[] = $area['area_id'];
                        }
                        if($parentArea->hasChild){
                            foreach($parentArea->children as $childArea){
                                if(!in_array($childArea->id, $hasRegisterList)){
                                    $result[] = [
                                        'area_id' => $childArea->id,
                                        'area_name' => $childArea->name,
                                        'area_level' => $childArea->level->level,
                                        'quantity' => 0,
                                    ];
                                }
                            }
                        }
                        return $result;
                    },
                ],
            ]),
        ];
    }
}
