<?php
/**
 * Created by PhpStorm.
 * User: forrest
 * Date: 07/06/18
 * Time: 15:15
 */

namespace business\modules\data\models;

use business\models\parts\Account;
use business\models\parts\Area;
use business\models\parts\Role;
use common\ActiveRecord\BusinessAreaAR;
use common\ActiveRecord\CustomConsumptionStatisticsAR;
use common\ActiveRecord\CustomUserAR;
use common\models\Model;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\Query;

class ConsumedCustomModel extends Model
{
    const SCE_GET_INFO = 'get_info';

    public $area_id;
    public $is_consumed;
    public $current_page;
    public $page_size;

    public function scenarios()
    {
        return [
            self::SCE_GET_INFO => ['area_id', 'is_consumed', 'current_page', 'page_size'],
        ];
    }

    public function rules()
    {
        return [
            [['area_id', 'is_consumed', 'current_page', 'page_size'], 'required', 'message' => 9001],
            [
                ['area_id'],
                'exist',
                'targetClass' => BusinessAreaAR::className(),
                'targetAttribute' => ['area_id' => 'id'],
                'filter' => ['display' => Area::DISPLAY_ON],
                'message' => 13071,
            ],
            [
                ['is_consumed'],
                'in',
                'range' => [0, 1],
                'message' => 9002,
            ],
            [
                ['current_page', 'page_size'],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
        ];
    }

    public function getInfo()
    {
        if (Yii::$app->BusinessUser->account->area->level->level != Area::LEVEL_UNDEFINED && Yii::$app->BusinessUser->account->role->id != Role::SUPER_ADMIN) {
            if ((new Account(['id' => Yii::$app->user->id]))->getTopArea()->id != (new Area(['id' => $this->area_id]))->getTopArea()->id) {
                $this->addError('businessUserRole', 13081);
                return false;
            }
        }

        $areaLevel = (new Area(['id' => $this->area_id]))->level->level;
        switch ($areaLevel) {
            case 1:
                $areaLevelName = 'business_top_area_id';
                break;
            case 2:
                $areaLevelName = 'business_secondary_area_id';
                break;
            case 3:
                $areaLevelName = 'business_tertiary_area_id';
                break;
            case 4:
                $areaLevelName = 'business_quaternary_area_id';
                break;
            case 5:
                $areaLevelName = 'business_area_id';
                break;
            default:
                $this->addError('areaLevel', 13071);
                return false;
        }

        if (intval($this->is_consumed) === 0) {
            $comsumptionAmountCondition = 'pf_custom_consumption_statistics.daily_consumption_amount = :amount';
        } else {
            $comsumptionAmountCondition = 'pf_custom_consumption_statistics.daily_consumption_amount != :amount';
        }

        try {
            $provider = new ActiveDataProvider([
                'query' => (new Query)->select([
                    'account' => '`pf_custom_user`.`account`',
                    'mobile' => '`pf_custom_user`.`mobile`',
                    'daily_consumption_amount' => '`pf_custom_consumption_statistics`.`daily_consumption_amount`',
                ])
                    ->from('pf_custom_user')
                    ->leftJoin('pf_custom_consumption_statistics', 'pf_custom_user.id = pf_custom_consumption_statistics.custom_user_id')
                    ->where(["pf_custom_user.$areaLevelName" => $this->area_id])
                    ->andWhere($comsumptionAmountCondition, ['amount' => 0])
                    ->orderBy('daily_consumption_amount DESC'),
                'pagination' => [
                    'page' => $this->current_page - 1,
                    'pageSize' => $this->page_size,
                ],
            ]);

            $codes = array_map(function ($data) {
                $data['mobile'] = empty($data['mobile']) ? '' : $data['mobile'];
                return $data;
            }, $provider->models);

            return [
                'count' => $provider->count,
                'total_count' => $provider->totalCount,
                'codes' => $codes,
            ];
        } catch (\Exception $e) {
            $this->addError('provider', 13380);
            return false;
        }
    }
}