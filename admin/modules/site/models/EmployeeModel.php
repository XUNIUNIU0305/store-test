<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/3/9
 * Time: 下午6:58
 */

namespace admin\modules\site\models;

use admin\components\handler\EmployeeHandler;
use admin\models\parts\business\Employee;
use common\models\Model;

class EmployeeModel extends Model
{

    const SCE_ADD_EMPLOYEE = 'add_employee';
    const SCE_EDIT_EMPLOYEE = 'edit_employee';
    const SCE_REMOVE_EMPLOYEE = 'remove_employee';
    const SCE_LIST_EMPLOYEE = 'get_list';

    public $id;
    public $set_employee;
    public $current_page;
    public $page_size;
    public $search;


    public function scenarios()
    {
        return [
            self::SCE_ADD_EMPLOYEE => [
                'set_employee'
            ],
            self::SCE_EDIT_EMPLOYEE => [
                'id',
                'set_employee',
            ],
            self::SCE_REMOVE_EMPLOYEE => [
                'id',
            ],

            self::SCE_LIST_EMPLOYEE => [
                'current_page',
                'page_size',
                'search',
            ],


        ];
    }

    public function rules()
    {
        return [
            [
                ['current_page'],
                'default',
                'value' => 1,
            ],
            [
                ['page_size'],
                'default',
                'value' => 999999,
            ],
            [
                ['current_page', 'page_size'],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
            [
                [
                    'id',
                    'set_employee',
                    'current_page',
                    'page_size'
                ],
                'required',
                'message' => 9001,
            ],
            [
                ['set_employee'],
                'common\validators\Admin\AdminEmployeeVaildator',
                'messageSmall'=>5187, //太短
                'messageLong'=>5188, //太长
                'message' => 5189,//含特殊字符
            ],

        ];
    }


    public function getList(){
        $searchData = [];
        if (!empty($this->search) && current($this->search)){
            $searchData = ['like', 'name',current($this->search)];
        }
        $employees= EmployeeHandler::provideEmployees($this->current_page, $this->page_size,$searchData);
        return [
            'count' => $employees->count,
            'total_count' => $employees->totalCount,
            'codes' =>$employees->models,
        ];
    }

    //新增
    public function addEmployee()
    {
        if (EmployeeHandler::create($this->set_employee))
        {
            return true;
        }
        $this->addError('addEmploy', 5180);
        return false;

    }


    //编辑
    public function editEmployee()
    {
        $employee = new Employee([
            'id' => $this->id,
        ]);
        if ($employee->setEmployee($this->set_employee) !== false)
        {
            return true;
        }

        $this->addError('editEmploy', 5181);
        return false;
    }


    //删除
    public function removeEmployee()
    {

        if ($this->id != 1 && EmployeeHandler::delete($this->id) )
        {
            return true;
        }
        $this->addError('removeEmploy', 5182);
        return false;
    }


}