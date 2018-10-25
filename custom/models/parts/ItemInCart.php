<?php
namespace custom\models\parts;

use Yii;
use common\models\parts\Item;
use common\models\RapidQuery;
use common\ActiveRecord\ShoppingCartAR;
use yii\base\InvalidCallException;
use common\models\parts\custom\CustomUser;

class ItemInCart extends Item{

    protected $userId;

    /*
     * Mod:Jiangyi
     * Date:2017/03/31
     * Desc:备注信息
     */
    private $comments;

    public function init(){
        parent::init();
        if(!$this->userId = Yii::$app->user->id)throw new InvalidCallException;
    }

    /**
     * 获取购物车内该Item的数量
     *
     * @return integer
     */
    public function getCount(){
        return (int)(new RapidQuery(new ShoppingCartAR))->scalar([
            'select' => ['count'],
            'where' => [
                'custom_user_id' => $this->userId,
                'product_sku_id' => $this->id,
            ],
        ]);
    }

    //设置备注
    public function setComments($comments){
        $this->comments = $comments;
    }

    //获取备注信息
    public function getComments(){
        return $this->comments;
    }



    /**
     * 获取用户ID
     *
     * @return int
     */
    public function getUserId(){
        return $this->userId;
    }

    public function getCustomUser(){
        return new CustomUser(['id' => $this->userId]);
    }
}
