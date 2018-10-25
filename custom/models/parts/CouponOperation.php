<?php
namespace custom\models\parts;

use Yii;
use yii\base\Object;
use yii\base\InvalidConfigException;
use common\models\parts\coupon\CouponRecord;
use common\models\parts\Product;

class CouponOperation extends Object{

    public $items;
    public $tickets;

    private $_items;
    private $_tickets;
    private $_itemsFee;
    private $_supplierTickets;

    public function init(){
        if($this->validateItems()){
            $this->_items = $this->items;
        }else{
            throw new InvalidConfigException('unavailable items');
        }
        if($this->validateTickets()){
            $this->_tickets = $this->tickets;
        }else{
            throw new InvalidConfigException('unavailable tickets');
        }
    }

    public function resetData(){
        $this->_itemsFee = null;
        $this->_supplierTickets = null;
    }

    private function validateItems(){
        if(!$this->items)return false;
        try{
            foreach($this->items as $supplier){
                foreach($supplier as $productType => $items){
                    if(!in_array($productType, [Product::TYPE_STANDARD, Product::TYPE_CUSTOMIZATION]))return false;
                    foreach($items as $item){
                        if(!($item instanceof ItemInCart))return false;
                    }
                }
            }
            return true;
        }catch(\Exception $e){
            return false;
        }
    }

    private function validateTickets(){
        if(!$this->tickets)return false;
        try{
            foreach($this->tickets as $ticket){
                if(!($ticket instanceof CouponRecord))return false;
            }
            return true;
        }catch(\Exception $e){
            return false;
        }
    }

    private function generateItemsFee(){
        if(is_null($this->_itemsFee)){
            foreach($this->_items as $supplierId => $supplier){
                foreach($supplier as $productType => $items){
                    if($productType == Product::TYPE_STANDARD){
                        $totalFee = 0;
                        foreach($items as $item){
                            $totalFee += $item->price * $item->count;
                        }
                        $this->_itemsFee[$supplierId][$productType] = $totalFee;
                    }else{
                        foreach($items as $item){
                            $this->_itemsFee[$supplierId][$productType][$item->id] = $item->price;
                        }
                    }
                }
            }
        }
        return $this->_itemsFee;
    }

    private function generateTicketsGroupBySupplier(){
        if(is_null($this->_supplierTickets)){
            foreach($this->_tickets as $ticket){
                $this->_supplierTickets[$ticket->coupon->supplier->id][] = $ticket;
            }
        }
        return $this->_supplierTickets;
    }

    /**
     * 校验优惠券是否全部可用
     */
    public function isTicketsAvailable(){
        $supplierTickets = $this->generateTicketsGroupBySupplier();
        $this->generateItemsFee();
        foreach($supplierTickets as $supplierId => $tickets){
            foreach($tickets as $ticket){
                if(!$this->isTicketAvailable($ticket, $supplierId, false))return false;
            }
        }
        return true;
    }

    /**
     * 获取每订单适用的优惠券
     */
    public function getSuitableTickets(){
        $supplierTickets = $this->generateTicketsGroupBySupplier();
        $itemsFee = $this->generateItemsFee();
        $list = $this->generateNoSuitableTickets();
        foreach($supplierTickets as $supplierId => $tickets){
            foreach($tickets as $ticket){
                if(isset($this->_itemsFee[$supplierId])){
                    foreach($this->_itemsFee[$supplierId] as $productType => $totalFee){
                        if($productType == Product::TYPE_STANDARD){
                            if($this->validateTicket($ticket, $totalFee))$list[$supplierId][$productType][] = $ticket;
                        }else{
                            foreach($totalFee as $itemId => $fee){
                                if($this->validateTicket($ticket, $fee))$list[$supplierId][$productType][$itemId][] = $ticket;
                            }
                        }
                    }
                }
            }
        }
        return $list;
    }

    /**
     * 获取分组（可用、不可用）的优惠券
     */
    public function getDevideTickets(){
        $supplierTickets = $this->generateTicketsGroupBySupplier();
        $this->generateItemsFee();
        $devideTickets = [
            'valid' => [],
            'invalid' => [],
        ];
        foreach($supplierTickets as $supplierId => $tickets){
            foreach($tickets as $ticket){
                if($this->isTicketAvailable($ticket, $supplierId, false)){
                    $devideTickets['valid'][] = $ticket;
                }else{
                    $devideTickets['invalid'][] = $ticket;
                }
            }
        }
        return $devideTickets;
    }

    private function generateNoSuitableTickets(){
        $tickets = $this->generateItemsFee();
        return $this->setValueToArray($tickets);
    }

    private function setValueToArray(array $array){
        foreach($array as &$v){
            if(is_array($v)){
                $v = $this->setValueToArray($v);
            }else{
                $v = [];
            }
        }
        return $array;
    }

    private function isTicketAvailable(CouponRecord $ticket, $supplierId, $return = 'throw'){
        $this->generateItemsFee();
        try{
            foreach($this->_itemsFee[$supplierId] as $productType => $itemsFee){
                if($productType == Product::TYPE_STANDARD){
                    if($this->validateTicket($ticket, $itemsFee))return true;
                }else{
                    foreach($itemsFee as $itemFee){
                        if($this->validateTicket($ticket, $itemFee))return true;
                    }
                }
            }
            return false;
        }catch(\Exception $e){
            return Yii::$app->EC->callback($return, $e);
        }
    }

    private function validateTicket(CouponRecord $ticket, $totalFee){
        return ($ticket->coupon->price < $totalFee && $ticket->coupon->consumptionLimit <= $totalFee);
    }
}
