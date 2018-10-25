<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/3/6
 * Time: 下午6:05
 */

namespace admin\modules\site\models;


use admin\components\handler\CarouselHandler;
use admin\components\handler\CarouselBrandFloorHandler;
use admin\models\parts\OSSUploadConfigForAdmin;
use admin\models\parts\template\Carousel;
use admin\models\parts\template\CarouselBrandFloor;
use common\models\Model;
use common\models\parts\OSSImage;
use Yii;

class CarouselModel extends Model
{
    const CAR_GET_CAROUSEL = 'get_carousel';
    const CAR_UPDATE_CAROUSEL = 'update_carousel';
    const CAR_INSERT_CAROUSEL = 'insert_carousel';
    const CAR_DELETE_CAROUSEL = 'delete_carousel';
    const CAR_GET_OSS_PERMISSION = 'get_oss_permission';
    const SCE_SORT = 'save_sort';

    //主键
    public $id;
    public $file_name;
    public $product_url;
    public $sort;

    //oss授权
    public $file_suffix;


    //图片对象
    public $images;

    //分页
    public $current_page;
    public $page_size;

    //排序
    public $sort_items = [];

    public function scenarios()
    {
        return [
            self::CAR_UPDATE_CAROUSEL => [
                'id',
                'file_name',
                'product_url',
                'sort',
            ],

            self::CAR_INSERT_CAROUSEL => [
                'file_name',
                'product_url',
//                'sort',
            ],
            self::CAR_GET_CAROUSEL => [
                'current_page',
                'page_size',
            ],
            self::CAR_DELETE_CAROUSEL => [
                'id'
            ],

            self::CAR_GET_OSS_PERMISSION => [
                'file_suffix'
            ],
            self::SCE_SORT => [
                'sort_items'
            ]
        ];
    }

    public function rules()
    {
        return [
            [
                [
                    'id',
//                    'sort'
                ],
                'integer',
            ],
            [
                [
                    'file_name',
                    'product_url',
                    'file_suffix'
                ],
                'string',
            ],

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
                [
                    'file_name',
                    'product_url',
//                    'sort',
                    'current_page',
                    'page_size',
                    'sort_items'
                ],
                'required',
                'message' => 9001,
            ],
            [
                [
                    'current_page',
                    'page_size'
                ],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],


        ];
    }


    /**
     *====================================================
     * 获取轮播列表数据
     * @return array
     * @author shuang.li
     * @Date:2017年3月8日
     *====================================================
     */
    public function getCarousel()
    {
        $carouselProvider = CarouselHandler::provideCarousel($this->current_page, $this->page_size);
        return [
            'count' => $carouselProvider->count,
            'total_count' => $carouselProvider->totalCount,
            'codes' => $carouselProvider->models,
        ];

    }

    /**
     *====================================================
     * 更新轮播图
     * @return bool
     * @author shuang.li
     * @Date:2017年3月8日
     *====================================================
     */
    public function updateCarousel()
    {
        $images = new OSSImage([
            'images' => ['filename' => $this->file_name],
        ]);

        //更新对象
        $updateObj = new CarouselBrandFloor([
            'id' => $this->id,
            'type' => 1,
        ]);

        $data = [
            'file_name' => $this->file_name,
            'img_url' => current($images->getPath()),
            'product_url' => $this->product_url,
            'sort' => $this->sort,
        ];

        if ($updateObj->updateCarousel($data) !== false)
        {
            return true;
        }

        $this->addError('updateCarousel', 5104);
        return false;

    }

    /**
     *====================================================
     * 新增数据
     * @return bool
     * @author shuang.li
     * @Date:2017年3月15日
     *====================================================
     */
    public function insertCarousel()
    {
        //创建oss图片对象
        $images = new OSSImage([
            'images' => ['filename' => $this->file_name],
        ]);

        //外面传入新增的数据
        $data = [
            'file_name' => $this->file_name,
            'img_url' => current($images->getPath()),
            'product_url' => $this->product_url,
//            'sort' => $this->sort,
        ];

        //写入数据库
        $modelCarousel = new Carousel();
        if ($modelCarousel->add($data))
        {
            return true;
        }
        $this->addError('insertCarousel', 5090);
        return false;

    }


    public function deleteCarousel()
    {
        $deleteObj = new CarouselBrandFloor([
            'id' => $this->id,
            'type' => 1
        ]);

        $carouselModel = new Carousel();
        if ($carouselModel->delete($deleteObj))
        {
            return true;
        }

        $this->addError('deleteCarousel', 5092);
        return false;
    }

    /**
     *====================================================
     * OSS上传授权
     * @return array|bool
     * @author shuang.li
     * @Date:2017年3月8日
     *====================================================
     */
    public function getOssPermission()
    {
        $uploadConfig = new OSSUploadConfigForAdmin([
            'userId' => Yii::$app->user->id,
            'fileSuffix' => $this->file_suffix,
        ]);
        if ($permission = $uploadConfig->getPermission())
        {
            return $permission;
        }
        $this->addError('getOssPermission', 5093);
        return false;

    }

    /**
     * 排序
     * @return bool
     */
    public function saveSort()
    {
        if(!is_array($this->sort_items)){
            $this->addError('sort_items', 9002);
            return false;
        }
        try{
            return Carousel::updateSort($this->sort_items);
        } catch (\Exception $e){
            $this->addError('sort_items', 9002);
            return false;
        }
    }
}
