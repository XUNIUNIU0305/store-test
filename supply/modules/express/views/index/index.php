<?php
/**
 * @var $this \yii\web\View
 */

$this->title = '常用物流设置';
$asset = \supply\modules\express\assets\ExpressAsset::register($this);
$asset->js[] = 'js/index.js';
$asset->css[] = 'css/index.css';
?>
<script src="https://cdn.bootcss.com/lodash.js/4.17.4/lodash.min.js"></script>

<!-- tab start -->
<ul class="nav nav-tabs part-of-express">
  <li class="active">
    <a href="#settingForExpress" aria-controls="settingForExpress" data-toggle="tab">常用物流设置</a>
  </li>
  <!-- <li>
    <a href="#settingForCost" aria-controls="settingForCost" data-toggle="tab">运费模版设置</a>
  </li> -->
</ul>

<!-- search start -->
<div id="search">
  <span>物流公司：</span>
  <div>
    <input type="text" />
    <div></div>
  </div>
  <button>搜索</button>
</div>
<!-- search end -->

<div class="tab-content">
  <div class="tab-pane active" id="settingForExpress"></div>
  <!-- <div class="tab-pane" id="settingForCost">
    运费
  </div> -->
</div>
<!-- tab end -->

<!--pagination start-->
<div class="pull-right" id="wrapPaginationOfExpress"></div>
<!-- pagination end -->
