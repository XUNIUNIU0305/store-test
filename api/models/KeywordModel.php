<?php
namespace api\models;

use common\ActiveRecord\HomepageKeywordsAR;
use common\models\Model;
use Yii;

class KeywordModel extends Model
{
    const KW_GET_KEYWORDS = 'get_keywords';


    public function scenarios()
    {
        return [
            self::KW_GET_KEYWORDS => [],
        ];
    }

    public function rules()
    {
        return [];
    }

    /*
     * 获得所有楼层的所有分组的所有商品
     * 
     * @return Array
     */
    public function getKeywords()
    {
        if(false === $result = Yii::$app->RQ->AR(new HomepageKeywordsAR())->all([
            'select' => [
                'id',
                'name'
            ],
            'orderBy' => [
                'id' => SORT_DESC
            ]
        ])) {
            $this->addError('getKeywords', 5402);
            return false;
        }
        return $result;
    }
}
