<?php
namespace admin\modules\site\models;
use common\ActiveRecord\BrandShopAdvAR;
use common\ActiveRecord\SupplyUserAR;
use common\models\Model;
use common\models\parts\brand\ShopAdv;
use common\models\parts\OSSImage;
use common\models\parts\supply\SupplyShop;

class ShopindexModel extends Model
{
    const SCE_LIST = 'get_list';
    const SCE_EDIT = 'edit';
    const SCE_CREATE = 'create';

    public $id;
    public $supply_user_id;
    public $file_name;
    public $image_url;
    public $type;
    public $sort;




    //排序值取值范围
    public $_sort_range=[
        ShopAdv::SORT_TOP_ADV,
        ShopAdv::SORT_HOT_FIRST_ADV,
        ShopAdv::SORT_HOT_SECOND_ADV,
        ShopAdv::SORT_SUB_SECOND_ADV,
        ShopAdv::SORT_SUB_FIRST_ADV,
        ShopAdv::SORT_SUB_THIRD_ADV
    ];

    //广告类型取值范围
    private $_adv_type=[
        ShopAdv::TYPE_SMALL_IMG,
        ShopAdv::TYPE_BIG_IMG,
        ShopAdv::TYPE_SUB_IMG,
    ];
    public function rules()
    {
        return [
            [['supply_user_id'], 'exist', 'targetClass' => SupplyUserAR::className(), 'targetAttribute' => 'id', 'message' => 5216],
            [['id','image_url','file_name','type','supply_user_id','sort'],'required','message'=>9001],
            [['id'],'integer','message'=>5097],
            [
                ['type'],
                'in',
                'range'=>$this->_adv_type,
                'message'=>5218
            ],
            [
                ['image_url'],
                'url',
                'message'=>5265,
            ],
            [
                ['sort'],
                'in',
                'range'=>$this->_sort_range,
                'message'=>5256,
            ],
            [
                ['sort'],
                'unique',
                'targetClass'=>BrandShopAdvAR::className(),
                'targetAttribute'=>['supply_user_id'=>'supply_user_id','type'=>'type','sort'=>'sort'],
                'message'=>5256,
            ],
            [
                ['id'],
                'exist',
                'targetClass'=>BrandShopAdvAR::className(),
                'targetAttribute'=>['id'=>'id'],
                'message'=>5219,
            ]
        ];

    }

    public function scenarios()
    {
         return [
             self::SCE_LIST => ['supply_user_id'],
             self::SCE_EDIT => ['id','image_url','file_name'],
             self::SCE_CREATE => ['supply_user_id','image_url','type','file_name','sort'],
         ];
    }

     //获取商户ID
    /**
     *====================================================
     * 获取店铺页面信息
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public function getList(){
        return ( new SupplyShop(['id'=>$this->supply_user_id]))->getShopAdv();
    }

    /**
     *====================================================
     * 创建店铺页面
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function create(){
        $supplyUser = new SupplyShop(['id'=>$this->supply_user_id]);
        if($id=$supplyUser->createShop($this->file_name,current($this->getImages()->getPath()),$this->image_url,$this->type,$this->sort)){
            return ['id'=>$id];
        }
        $this->addError('create',5214);
        return false;
    }


    /**
     *====================================================
     * 编辑店铺页面
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function edit(){
        if ((new ShopAdv(['id'=>$this->id]))->setMulti($this->file_name,current($this->getImages()->getPath()),$this->image_url) !== false){
            return true;
        }
        $this->addError('edit',5214);
        return false;
    }

    private function getImages(){
        return new OSSImage(['images' => ['filename' => $this->file_name ]]);
    }
}