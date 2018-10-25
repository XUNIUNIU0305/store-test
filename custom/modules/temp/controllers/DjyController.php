<?php
namespace custom\modules\temp\controllers;

class DjyController extends \mobile\modules\member\controllers\DjyController{

    public $layout = 'blank';

    public function init(){
        parent::init();
        $this->module->layoutPath = '@temp/views/layouts';
    }
}
