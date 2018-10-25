<?php
namespace console\controllers;

use Yii;
use console\controllers\basic\Controller;
use yii\helpers\Console;

/**
 * 命令行应用使用示例
 */
class DemoController extends Controller{

    public $option;
    public $otherOption;

    public function options($actionID){
        return [
            'option',
            'otherOption',
        ];
    }

    public function optionAliases(){
        return [
            'o' => 'option',
            'O' => 'otherOption',
        ];
    }

    /**
     * 访问默认方法
     *
     * type: 'demo' or 'demo/index'
     */
    public function actionIndex(){
        $this->stdout("Hello World\n", Console::BOLD);
        return 0;
    }

    /**
     * 访问指定方法
     *
     * type: 'demo/specify-action'
     */
    public function actionSpecifyAction(){
        $this->stdout("Hello World Again\n", Console::BOLD);
        return 0;
    }

    /**
     * 访问方法并添加选项及参数
     *
     * type: 'demo/params [--option=option_value][--otherOption=otheroption_value] [first_param] [second_param]'
     * option alias: o => option
     *               O => otherOption
     */
    public function actionParams($firstParam = null, $secondParam = null, array $thirdParam = null){
        $this->stdout("Hello World Third Time", Console::BOLD);
        if(!is_null($firstParam) || !is_null($this->option) || !is_null($this->otherOption)){
            $this->stdout("\nWith ");
        }
        if(is_null($firstParam)){
            $paramsExist = false;
        }else{
            $paramsExist = true;
            $this->stdout('PARAMS: ', Console::FG_RED);
            $this->stdout('{');
            $paramsPosition = ['first', 'second', 'third'];
            is_null($firstParam) or $params[] = $firstParam;
            is_null($secondParam) or $params[] = $secondParam;
            is_null($thirdParam) or $params[] = $thirdParam;
            $i = 1;
            foreach($params as $key => $param){
                $this->stdout($paramsPosition[$key] . ' => ');
                if($key == 2){
                    $this->stdout('[ ');
                    $o = 1;
                    foreach($param as $k => $p){
                        $this->stdout($p, Console::UNDERLINE);
                        if($o < count($param)){
                            $this->stdout(', ');
                        }
                        $o++;
                    }
                    $this->stdout(' ]');
                }else{
                    $this->stdout($param, Console::UNDERLINE);
                }
                if($i < count($params)){
                    $this->stdout('; ');
                }
                $i++;
            }
            $this->stdout("}");
        }
        if($paramsExist && (!is_null($this->option) || !is_null($this->otherOption))){
            $this->stdout("\nAnd ");
        }
        if(!is_null($this->option) || !is_null($this->otherOption)){
            $this->stdout('OPTIONS: ', Console::FG_RED);
            $this->stdout('{');
            if(!is_null($this->option)){
                $optionExist = true;
                $this->stdout('option => ');
                $this->stdout($this->option, Console::UNDERLINE);
            }else{
                $optionExist = false;
            }
            if($optionExist && !is_null($this->otherOption)){
                $this->stdout('; ');
            }
            if(!is_null($this->otherOption)){
                $this->stdout('otherOption => ');
                $this->stdout($this->otherOption, Console::UNDERLINE);
            }
            $this->stdout('}');
        }
        $this->stdout("\n");
        return 0;
    }
}
