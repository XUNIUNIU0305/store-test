<?php
namespace custom\models;

use common\components\handler\OSSImageHandler;
use Yii;
use common\models\Model;
use custom\models\parts\ItemInCart;
use custom\models\parts\UrlParamCrypt;

class CartModel extends Model{

    const SCE_GET_LIST = 'get_list';
    const SCE_CHANGE_ITEM_COUNT = 'change_item_count';
    const SCE_REMOVE_ITEMS = 'remove_items';
    const SCE_PLACE_ORDER = 'place_order';

    public $current_page;
    public $page_size;
    public $item_id;
    public $count;
    public $items_id;

    public function scenarios(){
        return [
            self::SCE_GET_LIST => [
                'current_page',
                'page_size',
            ],
            self::SCE_CHANGE_ITEM_COUNT => [
                'item_id',
                'count',
            ],
            self::SCE_REMOVE_ITEMS => [
                'items_id',
            ],
            self::SCE_PLACE_ORDER => [
                'items_id',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['current_page'],
                'default',
                'value' => 1,
            ],
            [
                ['page_size'],
                'default',
                'value' => 10,
            ],
            [
                ['current_page', 'page_size', 'item_id', 'count', 'items_id'],
                'required',
                'message' => 9001,
            ],
            [
                ['current_page', 'page_size', 'count'],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
            [
                ['item_id'],
                'common\validators\item\IdValidator',
                'userId' => Yii::$app->user->id,
                'count' => $this->count,
                'message' => 3041,
            ],
            [
                ['items_id'],
                'each',
                'rule' => [
                    'common\validators\item\IdValidator',
                    'userId' => Yii::$app->user->id,
                    'count' => $this->scenario == self::SCE_PLACE_ORDER ? true : null,
                ],
                'allowMessageFromRule' => false,
                'message' => 3051,
            ],
        ];
    }

    public function placeOrder(){
        $urlCrypt = new UrlParamCrypt;
        return $urlCrypt->encrypt($this->items_id);
    }

    public function removeItems(){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            foreach($this->items_id as $item){
                if(Yii::$app->CustomUser->cart->removeItem(new ItemInCart(['id' => $item])) === false)throw new \Exception;
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            $this->addError('removeItems', 3052);
            return false;
        }
    }

    public function changeItemCount(){
        $itemInCart = current(Yii::$app->CustomUser->cart->getItems((array)$this->item_id));
        $diffCount = $itemInCart->count - $this->count;
        if($diffCount > 0){
            $result = Yii::$app->CustomUser->cart->removeItem($itemInCart, $diffCount);
        }else if($diffCount < 0){
            $result = Yii::$app->CustomUser->cart->addItem($itemInCart, $diffCount * -1);
        }else{
            $result = true;
        }
        if(!$result){
            $this->addError('changeItemCount', 3042);
            return false;
        }
        return [
            'stock' => $itemInCart->stock,
            'count' => $itemInCart->count,
        ];
    }

    public function getList(){
        $ActiveDataProvider = Yii::$app->CustomUser->cart->provideItems($this->current_page, $this->page_size);
        $itemsId = array_column($ActiveDataProvider->getModels(), 'product_sku_id');
        $itemsGroupBySuppliers = Yii::$app->CustomUser->cart->getItemsGroupBySuppliers($itemsId);
        return [
            'count' => $ActiveDataProvider->count,
            'total_count' => $ActiveDataProvider->totalCount,
            'items' => array_map(function($suppliers){
                $suppliers['supplier'] = $suppliers['supplier']->storeName;
                $suppliers['items'] = array_map(function($item){
                    $ossImageHandlerObj = OSSImageHandler::load($item->mainImage);
                    $ossSize = $ossImageHandlerObj->resize(92,92);
                    $image =  $ossSize->apply() ? $ossSize->image->path : '';

                    return [
                        'id' => $item->id,
                        'product_id' => $item->productId,
                        'title' => $item->title,
                        'image' => $image,
                        'attributes' => array_map(function($attribute){
                            $attribute['selected_option'] = $attribute['selectedOption'];
                            unset($attribute['selectedOption']);
                            return $attribute;
                        }, $item->attributes),
                        'price' => $item->price,
                        'count' => $item->count,
                        'stock' => $item->stock,
                        'sale_status' => $item->saleStatus,
                    ];
                }, $suppliers['items']);
                return $suppliers;
            }, $itemsGroupBySuppliers),
        ];
    }

    public static function getItemsQuantity(){
        return [
            'quantity' => Yii::$app->CustomUser->cart->allQuantity,
        ];
    }
}
