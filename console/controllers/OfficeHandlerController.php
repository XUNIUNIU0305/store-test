<?php
/**
 * Created by PhpStorm.
 * User: forrestgao
 * Date: 18-4-17
 * Time: 上午11:26
 */

namespace console\controllers;

use business\models\parts\Role;
use common\ActiveRecord\BusinessAreaAR;
use common\ActiveRecord\BusinessUserAR;
use Yii;
use business\models\parts\Account;
use business\models\parts\Area;
use common\ActiveRecord\CustomUserAR;
use common\models\parts\custom\CustomUser;
use console\controllers\basic\Controller;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\db\Exception;

class OfficeHandlerController extends Controller
{
    public function actionIndex($absoluteFilename = __DIR__ . '/user_level.xlsx')
    {
        return 0;
        if (!file_exists($absoluteFilename)) {
            echo "file not exists.\n";
            return 0;
        }

        // 初始化前置条件
        if(!$this->initInfo()){
            $this->stdout('initialize failed' . PHP_EOL);
            return 0;
        }

        if ($datas = $this->parseExcel($absoluteFilename)) {
            foreach ($datas as $k => $data) {
                $customAccount = trim($data['门店账号']);
                // 判断是否有门店账号

                try {
                    $custom = new CustomUser(['account' => $customAccount]);
                } catch (InvalidCallException $e) {
                    echo "门店账号 [$customAccount] 不存在\n\n";
                    $handledDataQuantity = $k + 1;
                    $this->stdout("已处理：{$handledDataQuantity}\n\n");
                    continue;
                }

                if($custom->status == 1){
                    $this->stdout('门店账号已禁用' . PHP_EOL);
                    $handledDataQuantity = $k + 1;
                    $this->stdout("已处理：{$handledDataQuantity}\n\n");
                    continue;
                }

                $top = $this->parseField($data['钉钉省公司']);

                if ($top == '总部') {
                    echo "门店账号 [$customAccount] 为总部\n";
                    $handledDataQuantity = $k + 1;
                    $this->stdout("已处理：{$handledDataQuantity}\n\n");
                    continue;
                }

                $topMobile = $this->parseField($data['手机号']);
                $secondary = $this->parseField($data['辅导老师']);
                $secondaryMobile = $this->parseField($data['辅导老师手机']);
                $tertiary = $this->parseField($data['督导老师']);
                $tertiaryMobile = $this->parseField($data['督导老师手机']);
                $quaternary = $this->parseField($data['钉钉运营商']);
                $quaternaryMobile = $this->parseField($data['运营商手机']);
                $quaternaryId = $this->parseField($data['运营商ID']);
                $team = $this->parseField($data['所属小组']);

                if (empty($top) || empty($secondary) || empty($tertiary) || empty($quaternary) || empty($team)) {
                    $this->stdout("五级信息缺失\n\n");
                    $handledDataQuantity = $k + 1;
                    $this->stdout("已处理：{$handledDataQuantity}\n\n");
                    continue;
                }

                $userInfo = [
                    'top' => $top,
                    'topMobile' => $topMobile,
                    'secondary' => $secondary,
                    'secondaryMobile' => $secondaryMobile,
                    'tertiary' => $tertiary,
                    'tertiaryMobile' => $tertiaryMobile,
                    'quaternary' => $quaternary,
                    'quaternaryMobile' => $quaternaryMobile,
                    'quaternaryId' => $quaternaryId,
                    'team' => $team,
                    'customAccount' => $customAccount

                ];

                if ($this->handle($userInfo, $custom)) {
                    echo "门店账号 [$customAccount] 操作成功\n";
                    $handledDataQuantity = $k + 1;
                    $this->stdout("已处理：{$handledDataQuantity}\n\n");
                } else {
                    echo "门店账号 [$customAccount] 操作失败\n";
                    return 0;
                }
            }

        }


    }

    /**
     * 添加 area 和绑定 business、custom
     */
    private function handle($userInfo, $custom)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $time = 5;
            $businessAreaId = 0;
            while ($time > 0) {
                switch ($time) {
                    case 5:
                        $mobile = $userInfo['topMobile'];
                        $level = 1;
                        break;
                    case 4:
                        $mobile = $userInfo['secondaryMobile'];
                        $level = 2;
                        break;
                    case 3:
                        $mobile = $userInfo['tertiaryMobile'];
                        $level = 3;
                        break;
                    case 2:
                        $quaternaryId = $userInfo['quaternaryId'];
                        $level = 4;
                        break;
                    case 1:
                        $level = 5;
                        break;
                }

                // 1.生成五级
                $area = $this->addArea($userInfo, $businessAreaId, $level, $newArea);
                $businessAreaId = $area->id;

                // 2.绑定business
                if($newArea){
                    try {
                        if (isset($quaternaryId)) {
                            $account = new Account(['account' => $quaternaryId]);
                            unset($quaternaryId);
                        } else {
                            $account = new Account(['mobile' => $mobile]);
                            unset($mobile);
                        }
                        if ($account->getRole()->id != Role::UNDEFINED) {
                            echo "账号角色已定义\n";
                            throw new \Exception;
                        }
                        if ($account->status != Account::STATUS_NORMAL) {
                            echo "账号状态不正常\n";
                            throw new \Exception;
                        }
                        $area->setUser(Area::PERSON_LEADER, $account);
                        echo "账号[{$account->account}]角色绑定成功\n";
                    } catch (\Exception $e) {
                    }
                }

                // 3.绑定custom
                if ($area->getLevel()->level == 5) {
                    $area->bindCustom($custom->account);
                }
                --$time;
            }

            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            var_dump($e->getMessage(), $e->getFile(), $e->getLine());exit;
            return false;
        }

    }

    /**
     * 添加 area
     */
    private $_allAreas;

    private function addArea($userInfo, $parentBusinessAreaId, $level, &$newArea)
    {
        switch ($level) {
            case Area::LEVEL_TOP:
                $areaName = $userInfo['top'];
                $level = Area::LEVEL_TOP;
                break;
            case Area::LEVEL_SECONDARY:
                $areaName = $userInfo['secondary'];
                $level = Area::LEVEL_SECONDARY;
                break;
            case Area::LEVEL_TERTIARY:
                $areaName = $userInfo['tertiary'];
                $level = Area::LEVEL_TERTIARY;
                break;
            case Area::LEVEL_QUATERNARY:
                $areaName = $userInfo['quaternary'];
                $level = Area::LEVEL_QUATERNARY;
                break;
            case Area::LEVEL_FIFTH:
                $areaName = $userInfo['team'];
                $level = Area::LEVEL_FIFTH;
                break;
            default:
                break;
        }
        if(isset($this->_allAreas[$parentBusinessAreaId][$areaName])){
            $this->stdout("区域：[{$areaName}] 存在" . PHP_EOL);
            $newArea = false;
            return new Area(['id' => $this->_allAreas[$parentBusinessAreaId][$areaName]]);
        }else{
            $this->stdout('添加区域：' . $areaName . PHP_EOL);
            $newArea = true;
            $a = (new Area(['id' => $parentBusinessAreaId]))->addChild($areaName);
            $this->_allAreas[$parentBusinessAreaId][$areaName] = $a->id;
            return $a;
        }
        //if ($id = BusinessAreaAR::find()->select(['id'])->where([
            //'parent_business_area_id' => $parentBusinessAreaId,
            //'name' => $areaName,
            //'display' => 1,
        //])->scalar()) {
            //$this->stdout("区域：[{$areaName}] 存在" . PHP_EOL);
            //$newArea = false;
            //return new Area(['id' => $id]);
        //}
        //if (!empty($areaName)) {
            //$this->stdout('添加区域：' . $areaName . PHP_EOL);
            //$newArea = true;
            //return (new Area(['id' => $parentBusinessAreaId]))->addChild($areaName);
        //}
    }

    /**
     * 过滤字符串
     * @param string $field
     * @return string
     * @author forrestgao
     */
    private function parseField($field)
    {
        if (is_null($field)) {
            return '';
        }
        $field = trim($field);
        if (strpos($field, '+86-') !== false) {
            return explode('+86-', $field)[1];
        }
        return $field;
    }

    /**
     * 读取 .xlsx 文件并返回数组
     * @param string $absoluteFilename only support .xlsx file
     * @return array | false
     * @author forrestgao
     */
    private function parseExcel($absoluteFilename)
    {
        $objPHPExcelReader = \PHPExcel_IOFactory::load($absoluteFilename);
        $data = [];
        try {
            foreach ($objPHPExcelReader->getWorksheetIterator() as $sheet) {
                $j = 0;
                foreach ($sheet->getRowIterator() as $row) {
                    if ($row->getRowIndex() < 2) {
                        $col = [];
                        $i = 0;
                        foreach ($row->getCellIterator() as $cell) {
                            $col[$i++] = $cell->getValue();
                        }
                        continue;
                    }

                    $tmpRow = [];
                    $i = 0;
                    foreach ($row->getCellIterator() as $cell) {
                        $tmpRow[$col[$i++]] = $cell->getValue();
                    }
                    $data[$j++] = $tmpRow;
                }
            }
        } catch (Exception $e) {
            throw new InvalidCallException('');
            return false;
        }
        if (is_null($data) || empty($data)) {
            return false;
        }
        return $data;
    }

    /**
     * 初始化信息
     */
    private function initInfo()
    {
        // 1.门店账号变成孤儿门店
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if(CustomUserAR::updateAll(['business_area_id' => 5]) === false)throw new \Exception;

            // 2.重置角色
            $businessUsers = BusinessUserAR::find()
                                            ->where(['not in', 'level', [10, 249]])
                                            ->all();
            if ($businessUsers) {
                foreach ($businessUsers as $businessUser) {
                    if(!$this->resetRoleOne($businessUser->account))throw new \Exception;
                }
            }

            // 3.删除区域
            if(BusinessAreaAR::updateAll(['display' => 0], 'id > 5') === false)throw new \Exception;
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * @param integer $businessAccount
     * @return boolean
     * @author forrestgao
     */
    private function resetRoleOne($businessAccount)
    {
        try {
            $account = new Account(['account' => $businessAccount]);
            if (!is_null($account)) {
                if ($account->resetRole()) {
                    return true;
                }
            }
            throw new InvalidCallException();
        } catch (Exception $e) {
            return false;
        }
    }

}
