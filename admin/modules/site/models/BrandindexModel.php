<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/4/27
 * Time: 下午4:32
 */

namespace admin\modules\site\models;

use common\ActiveRecord\BrandAdvAR;
use common\ActiveRecord\BrandHomeAR;
use common\components\handler\ShopBrandHandler;
use common\models\Model;
use common\models\parts\brand\BrandAdv;
use common\models\parts\brand\BrandHome;
use common\models\parts\OSSImage;

class BrandindexModel extends Model
{
    const SCE_HEADER_ADV_LIST = 'get_list_header_adv';
    const SCE_BIG_SMALL_ADV_LIST = 'get_list_big_small_adv';
    const SCE_HOT_BRAND_LIST = 'get_list_hot_brand';
    const SCE_BRAND_ALBUM_LIST = 'get_list_brand_album';

    const SCE_HEADER_ADV_CREATE = 'create_header_adv';
    const SCE_HOT_BRAND_CREATE = 'create_hot_brand';
    const SCE_BRAND_ALBUM_CREATE = 'create_brand_album';

    const SCE_HEADER_ADV_REMOVE = 'remove_header_adv';
    const SCE_BRAND_REMOVE = 'remove_brand';

    const SCE_HEADER_ADV_EDIT = 'edit_header_adv';
    const SCE_HOT_BRAND_EDIT = 'edit_hot_brand';
    const SCE_BRAND_ALBUM_EDIT = 'edit_brand_album';
    const SCE_SET_HOT_BRAND_STATUS = 'set_hot_brand_status';


    public $id;
    public $current_page;
    public $page_size;
    //pf_brand_adv
    public $url;//链接地址
    public $file_name;//图片路径

    public $position;//0：表示顶部大图 1：表示左侧小图 2：表示长图' 默认为0

    //pf_brand_home
    public $logo; //logo
    public $background; //背景
    public $title; //标题
    public $introduction; //说明
    public $header_sort; //排序
    public $hot_sort; //排序
    public $album_sort; //排序

    public $multi_data; //批量编辑
    public $status;


    public function scenarios()
    {
        return [
            self::SCE_HEADER_ADV_LIST => [],
            self::SCE_BIG_SMALL_ADV_LIST => [],
            self::SCE_HOT_BRAND_LIST => ['current_page', 'page_size',],
            self::SCE_BRAND_ALBUM_LIST => [],


            self::SCE_HEADER_ADV_CREATE => ['header_sort','file_name','url'],
            self::SCE_HOT_BRAND_CREATE => ['hot_sort','file_name','url'],
            self::SCE_BRAND_ALBUM_CREATE => ['album_sort','logo','background','url','title','introduction'],


            self::SCE_HEADER_ADV_EDIT => ['id','header_sort','file_name','url'],
            self::SCE_HOT_BRAND_EDIT => ['multi_data'],
            self::SCE_BRAND_ALBUM_EDIT => ['id','album_sort','logo','background','url','title','introduction'],


            self::SCE_HEADER_ADV_REMOVE => ['id'],
            self::SCE_BRAND_REMOVE => ['id'],
            self::SCE_SET_HOT_BRAND_STATUS => ['id','status'],

        ];
    }

    public function rules()
    {
        return [
            [
                ['current_page'],
                'default',
                'value'=>1,
            ],
            [
                ['page_size'],
                'default',
                'value'=>10,
            ],
            [
                ['url'],
                'url',
                'message'=>5265,
            ],
            [
                [
                    'id',
                    'file_name',
                    'url',
                    'current_page',
                    'page_size',
                    'album_sort',
                    'hot_sort',
                    'header_sort',
                    'logo',
                    'background',
                    'title',
                    'introduction',
                    'status',
                ],
                'required',
                'message' => 9001,
            ],

            [
                ['title'],
                'string',
                'length' => [
                    1,
                    10
                ],
                'tooShort' => 5262,
                'tooLong' => 5211,
                'message' => 5099,
            ],
            [
                ['introduction'],
                'string',
                'length' => [
                    1,
                    50
                ],
                'tooShort' => 5263,
                'tooLong' => 5264,
                'message' => 5099,
            ],
            [
                ['status'],
                'in',
                'range' => [
                    0,
                    1,
                    -1
                ],
                'message' => 5213,
                'on' => [self::SCE_SET_HOT_BRAND_STATUS]
            ],
            [
                ['id'],
                'integer',
                'message' => 5097,
                'on' => [
                    self::SCE_HEADER_ADV_EDIT,
                    self::SCE_BRAND_ALBUM_EDIT,
                    self::SCE_HEADER_ADV_REMOVE,
                    self::SCE_SET_HOT_BRAND_STATUS
                ]
            ],

            [
                ['multi_data'],
                'common\validators\Admin\AdminHotBrandVaildator',
                'message'=>5260,
                'sortMessage'=>5217,
                'urlMessage'=>5265,
                'on'=>[
                    self::SCE_HOT_BRAND_EDIT,
                ],
            ],

            [
                ['id'],
                'common\validators\Admin\AdminBrandAlbumVaildator',
                'message'=>5261,
                'on'=>[
                    self::SCE_BRAND_REMOVE,
                ],
            ],
            [
                ['header_sort'],
                'unique',
                'targetClass' => BrandAdvAR::className(),
                'targetAttribute' => 'sort',
                'filter' => ['position' => 0],
                'message' => 5217,
                'on' => [self::SCE_HEADER_ADV_CREATE]
            ],
            [
                ['hot_sort'],
                'unique',
                'targetClass' => BrandHomeAR::className(),
                'targetAttribute' => 'sort',
                'filter' => ['type' => BrandHomeAR::TYPE_HOT_BRAND],
                'message' => 5217,
                'on' => [self::SCE_HOT_BRAND_CREATE]
            ],
            [
                ['album_sort'],
                'unique',
                'targetClass' => BrandHomeAR::className(),
                'targetAttribute' => 'sort',
                'filter' => ['type' => BrandHomeAR::TYPE_BRAND_ALBUM],
                'message' => 5217,
                'on' => [self::SCE_BRAND_ALBUM_CREATE]
            ],

            [
                ['header_sort'],
                'unique',
                'targetClass' => BrandAdvAR::className(),
                'targetAttribute' => 'sort',
                'filter' => ['and','position = 0' ,' id <>'.$this->getId()],
                'message' => 5217,
                'on' => [self::SCE_HEADER_ADV_EDIT]
            ],


            [
                ['hot_sort'],
                'unique',
                'targetClass' => BrandHomeAR::className(),
                'targetAttribute' => 'sort',
                'filter' => ['and','type = '.BrandHomeAR::TYPE_HOT_BRAND,' id <>'.$this->getId()],
                'message' => 5217,
                'on' => [self::SCE_HOT_BRAND_EDIT]
            ],
            [
                ['album_sort'],
                'unique',
                'targetClass' => BrandHomeAR::className(),
                'targetAttribute' => 'sort',
                'filter' => ['and','type = '.BrandHomeAR::TYPE_BRAND_ALBUM,' id <>'.$this->getId()],
                'message' => 5217,
                'on' => [self::SCE_BRAND_ALBUM_EDIT]
            ],

        ];
    }

    protected function getId(){
        if (is_array($this->id)){
            return  0;
        }else{
            return $this->id;
        }
    }

    /**
     *====================================================
     * 获取主广告列表
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public function getListHeaderAdv()
    {
        return ShopBrandHandler::brandAdvList(BrandAdvAR::POSITION_BIG);
    }

    /**
     *====================================================
     * 获取小图和长图
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public function getListBigSmallAdv()
    {
        return ShopBrandHandler::brandAdvList([BrandAdvAR::POSITION_SMALL,BrandAdvAR::POSITION_LONG]);
    }

    /**
     *====================================================
     * 获取热销品牌列表
     * @return array
     * @author shuang.li
     *====================================================
     */
    public function getListHotBrand()
    {
        $hotBrandList =  ShopBrandHandler::hotBrandList($this->current_page,$this->page_size,[BrandHomeAR::STATUS_AVAILABLE,BrandHomeAR::STATUS_UNAVAILABLE]);
        return [
            'count' => $hotBrandList->count,
            'total_count' => $hotBrandList->totalCount,
            'codes' => $hotBrandList->models,
        ];
    }


    /**
     *====================================================
     * 获取品牌特辑列表
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public function getListBrandAlbum()
    {
        return ShopBrandHandler::brandAlbumList();
    }


    /**
     *====================================================
     * 创建主广告，小广告，长广告
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function createHeaderAdv()
    {
        try{
            if (ShopBrandHandler::createHeaderAdv($this->header_sort, $this->file_name, self::getImagesPath($this->file_name), $this->url) !== false) return true;
        }
        catch (\Exception $e)
        {
            $this->addError('createHeaderAdv', 5191);
            return false;
        }
    }


    /**
     *====================================================
     * 创建热销品牌
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function createHotBrand()
    {
        try{
            if (ShopBrandHandler::createBrand( $this->file_name,$this->hot_sort,self::getImagesPath($this->file_name),$this->url) !== false) return true;
        }catch (\Exception $e){
            $this->addError('createHotBrand', 5192);
            return false;
        }
    }

    /**
     *====================================================
     * 创建品牌特辑
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function createBrandAlbum()
    {
        try {
            if (ShopBrandHandler::createBrand('',$this->album_sort,'',$this->url,BrandHomeAR::TYPE_BRAND_ALBUM,$this->logo,self::getImagesPath($this->logo),$this->background,self::getImagesPath($this->background),$this->introduction,$this->title) !== false) return true;
        }catch (\Exception $e)
        {
            $this->addError('createBrandAlbum', 5193);
            return false;
        }
    }


    /**
     *====================================================
     * 编辑主广告
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function editHeaderAdv()
    {
        try{
            $adv = new BrandAdv(['id'=>$this->id]);
            if ($adv->setAdvInfo(['sort' => $this->header_sort,'file_name' => $this->file_name, 'path' => self::getImagesPath($this->file_name),'url'=>$this->url]) !== false) {
                return true;
            }

        }catch (\Exception $e){
            $this->addError('editHeaderAdv', 5194);
            return false;
        }
    }

    /**
     *====================================================
     * 编辑热门品牌
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function editHotBrand()
    {
        foreach ((array)$this->multi_data as $brandHome)
        {
            $adv = new BrandHome(['id' => $brandHome['id']]);
            if($adv->setHomeInfo(['sort' => $brandHome['hot_sort'], 'file_name' => $brandHome['file_name'], 'path' => self::getImagesPath($brandHome['file_name']), 'url' => $brandHome['url']]) === false){
                $this->addError('editHotBrand', 5195);
                return false;
            }
        }
        return true;
    }

    /**
     *====================================================
     * 编辑品牌特辑
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function editBrandAlbum()
    {
        $adv = new BrandHome(['id'=>$this->id]);
        if ($adv->setHomeInfo(['logo' => $this->logo,'background' => $this->background,'logo_path' => self::getImagesPath($this->logo),'background_path' => self::getImagesPath($this->background),'url' => $this->url,'sort' => $this->album_sort,'title' => $this->title,'introduction' => $this->introduction])!== false) {
            return true;
        }
        $this->addError('editBrandAlbum', 5196);
        return false;
    }


    /**
     *====================================================
     * 删除主广告
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function removeHeaderAdv()
    {
        $adv = new BrandAdv(['id'=>$this->id]);
        if (ShopBrandHandler::deleteBrandAdv($adv) !==false){
            return true;
        }
        $this->addError('removeHeaderAdv', 5197);
        return false;
    }

    /**
     *====================================================
     * 删除品牌
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function removeBrand()
    { 
        if (ShopBrandHandler::deleteBrandHome($this->id) !==false){
            return true;
        }
        $this->addError('removeBrand', 5210);
        return false;
    }


    /**
     *====================================================
     * 设置热门品牌状态
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function setHotBrandStatus(){

        $adv = new BrandHome(['id'=>$this->id]);
        if ($adv->setStatus($this->status) !==false){
            return true;
        }

        $this->addError('setHotBrandStatus', 5212);
        return false;
    }

    public function getImagesPath($fileName){
        return current((new OSSImage(['images' => ['filename' => $fileName]]))->getPath());
    }

}