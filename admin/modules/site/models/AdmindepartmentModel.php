<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/16
 * Time: 11:37
 */

namespace admin\modules\site\models;


use admin\components\handler\AdminDepartmentHandler;
use admin\models\parts\role\AdminAccount;
use admin\models\parts\role\AdminDepartment;
use common\models\Model;

class AdmindepartmentModel extends Model
{


    //定义常量
    const SCE_ADD_DEPARTMENT = "add_department";//新增部门
    const SCE_DEL_DEPARTMENT = "remove_department";//删除部门
    const SCE_MODIFY_DEPARTMENT = "modify_department";//编辑部门信息
    const SCE_GET_DEPARTMENT_LIST = "get_department_list";//获取部门列表
    const SCE_GET_DEPARTMENT_EMPLOYEE = "get_department_employee";//获取部门员工列表


    //定义属性
    public $id;
    public $name;
    public $introduction;

    public $page_size;
    public $current_page;

    public function scenarios()
    {
        return [
            self::SCE_ADD_DEPARTMENT => ['name', 'introduction'],
            self::SCE_DEL_DEPARTMENT => ['id'],
            self::SCE_MODIFY_DEPARTMENT => ['id', 'name', 'introduction'],
            self::SCE_GET_DEPARTMENT_LIST => ['current_page', 'page_size'],
            self::SCE_GET_DEPARTMENT_EMPLOYEE => ['id'],
        ];
    }


    //配置规则
    public function rules()
    {
        return [
            [
                ['name'],
                'required',
                'message' => 9001,
            ],
            [
                ['introduction'],
                'default',
                'value'=>'intro',
            ],
            [
                ['id'],
                'required',
                'message' => 9001,
            ],
            [
                ['page_size'],
                'default',
                'value' => 1000,
            ],
            [
                ['current_page'],
                'default',
                'value' => 1,
            ],
            [
                ['id','current_page','page_size'],
                'number',
                'integerOnly'=>true,
                'message'=>9001,
            ],
            //添加数据时，检测名称是否重名
            [
                ['name'],
                'common\validators\Admin\AdminDepartmentVaildator',
                'id'=>(int)$this->id,
                'name'=>$this->name,
                'message'=>5160,
                'on'=>[self::SCE_ADD_DEPARTMENT,self::SCE_MODIFY_DEPARTMENT]
            ],
            //删除部门时，验证部门信息是否存在
            [
                ['id'],
                'common\validators\Admin\AdminDepartmentVaildator',
                'id'=>$this->id,
                'message'=>5161,
                'on'=>[self::SCE_MODIFY_DEPARTMENT,self::SCE_DEL_DEPARTMENT,self::SCE_GET_DEPARTMENT_EMPLOYEE]
            ],

        ];
    }

    //添加部门
    public function addDepartment()
    {

        if (AdminDepartmentHandler::create($this->name, $this->introduction,false)) {
            return true;
        }
        $this->addError("addDepartment", 5111);
        return false;
    }

    //修改类别信息
    public function modifyDepartment()
    {
        if (false!==(new AdminDepartment(['id' => $this->id]))->setDepartmentInfo(['name' => $this->name, 'introduction' => $this->introduction],false)) {
            return true;
        }
        $this->addError("modifyDepartment", 5112);
        return false;
    }

    //删除部门
    public function removeDepartment()
    {
        if (AdminDepartmentHandler::delete(new AdminDepartment(["id" => $this->id]),false)) {
            return true;
        }
        $this->addError('removeDepartment', 5113);
        return false;
    }

    //获取部门员工列表
    public function getDepartmentEmployee()
    {
        return array_map(function (AdminAccount $item) {
            return [
                'id' => $item->id,
                'account' => $item->getAccount(),
                'name' => $item->getName(),
                'mobile' => $item->getMobile(),
                'email' => $item->getEmail(),
                'status' => $item->getStatus()
            ];
        },
            (new AdminDepartment(['id' => $this->id]))->getEmployees()
        );
    }

    //获取部门列表
    public function getDepartmentList()
    {
        $departmentList = AdminDepartmentHandler::getDepartmentList($this->current_page, $this->page_size);
        return [
            'count' => $departmentList->count,
            'total_count' => $departmentList->totalCount,
            'codes' => $departmentList->models,
        ];
    }


}