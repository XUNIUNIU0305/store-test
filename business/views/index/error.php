<?php
$this->params = ['css' => 'css/error.css', 'js' => 'js/error.js'];

if(YII_ENV == 'dev'){
     var_dump($name);
     var_dump($message);
     var_dump($exception);
}
?>
<div class="container apx-not-found">
    <!-- Not found -->
    <div class="text-center">
        <img src="../images/not_found.png">
        <h4>
            <strong>出错了!</strong>
            <small><strong class="high-lighted"><?= $name ?></strong></small>
        </h4>
        <a href="/" class="btn btn-danger btn-block btn-lg">返回首页</a>
    </div>
</div>
