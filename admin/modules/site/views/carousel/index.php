<?php
  $this->params = ['js' => ['js/site-index.js', 'js/carousel.js'], 'css' => ['css/site-index.css', 'css/carousel.css']];
?>
<div class="admin-frame-main">
  <div class="admin-main-wrap">
    <div class="admin-edit-index">
      <ul class="nav nav-tabs">
        <li>
          <a href="#edit_carousels" data-toggle="tab" data-id="edit_carousels">PC轮播</a>
        </li>
        <li>
          <a href="#edit_carousels_wap" data-toggle="tab" data-id="edit_carousels_wap">WAP轮播</a>
        </li>
        <li>
          <a href="#edit_classify" data-toggle="tab" data-id="edit_classify">分类</a>
        </li>
        <li>
          <a href="#edit_floors" data-toggle="tab" data-id="edit_floors">商品楼层</a>
        </li>
        <li>
          <a href="#edit_hot_keywords" data-toggle="tab" data-id="edit_hot_keywords">热搜关键字</a>
        </li>
        <!-- <li>
          <a href="#edit_notice" data-toggle="tab" data-id="edit_notice">公告</a>
        </li> -->
        <li>
          <a href="#edit_brands" data-toggle="tab" data-id="edit_brands">品牌</a>
        </li>
      </ul>
      <div class="tab-content">

        <div class="tab-pane carousel" id="edit_carousels">
          <button class="button primary" data-id="addNewCarsousel">
            <i class="fa fa-plus-circle" aria-hidden="true"></i>
            <span>添加轮播</span>
          </button>
          <div class="title">
            <i class="fa fa-th-large" aria-hidden="true"></i><span>轮播列表（<span id="carouselCount"></span>/<span id="maxCarouselCount" data-max-carousel-count="7">7</span>）</span>
          </div>
          <div id="carouselContent"></div>
        </div>

        <div class="tab-pane carousel-wap" id="edit_carousels_wap">
          <button class="button primary" data-id="addNewCarsouselWap">
            <i class="fa fa-plus-circle" aria-hidden="true"></i>
            <span>添加轮播</span>
          </button>
          <div class="title">
            <i class="fa fa-th-large" aria-hidden="true"></i><span>轮播列表（<span id="carouselWapCount"></span>/<span id="maxCarouselWapCount" data-max-carousel-wap-count="7">7</span>）</span>
          </div>
          <div id="carouselWapContent"></div>
        </div>

        <div class="tab-pane floor" id="edit_floors" role="tabpanel">
          <button class="button primary" data-id="addNewFloor">
            <i class="fa fa-plus-circle" aria-hidden="true"></i>
            <span>添加楼层</span>
          </button>
          <div class="title">
            <i class="fa fa-th-large" aria-hidden="true"></i><span>已有楼层</span>
          </div>
          <div id="floorContent"></div>
        </div>

        <div class="tab-pane classify" id="edit_classify">
          <div class="classify-panel">
            <header>一级栏目名称（<span id="titleCount"></span>/<span id="maxTitleCount" data-count="8">8</span>）</header>
            <div class="classify-panel-content" id="titleContent"></div>
            <footer>
              <button class="button primary">新增栏目</button>
            </footer>
          </div><div class="classify-panel">
            <header>下属品牌（<span id="brandCount"></span>/<span id="maxBrandCount" data-count="6">6</span>）</header>
            <div class="classify-panel-content" id="brandContent"></div>
            <footer>
              <button class="button primary">新增品牌</button>
            </footer>
          </div><div class="classify-panel">
            <header>下属分类（<span id="groupCount"></span>/<span id="maxGroupCount" data-count="30">30</span>）</header>
            <div class="classify-panel-content" id="groupContent"></div>
            <footer>
              <button class="button primary">新增分类</button>
            </footer>
          </div><div class="classify-panel">
            <header>关联ID</header>
            <div class="classify-panel-content" id="relationshipContent"></div>
          </div>
        </div>
        <!-- start hot_keywords -->
        <div class="tab-pane keywords" id="edit_hot_keywords">
            <div class="tab-pane hot_keywords">
              <button class="button primary">
                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                <span>关键字</span>
              </button>
            </div>
            <div class="title">
              <span>关键词列表</span>
            </div>
            <div class="keywordsContentList"></div>
            <div class="keywords-pop hidden">
              <h2>
                  <span class="modify-keyword">新增关键词</span>
                  <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">Close</span>
                  </a>
              </h2>
              <div class="addkeywordsContent">
                <span>请输入关键词</span>
                <input type="text" name="" value="" maxlength="6">
                <input type="button" name="" value="确定">
              </div>
            </div>
        </div>
        <!-- end hot_keywords -->

        <!-- start notice -->
        <!-- <div class="tab-pane notice" id="edit_notice">
          <header class="fontPages">
            <label>类型：</label>
            <div class="selecting">
              <select>
                <option value="-1">全部</option>
                <option value="1">网站公告</option>
                <option value="2">外部链接</option>
              </select>
            </div>
            <div class="searching">
              <input type="text" placeholder="输入发布人姓名">
              <i class="fa fa-search"></i>
            </div>
            <button class="button primary">
              <i class="fa fa-plus-circle"></i>
              <span class="addNotice">添加新公告</span>
            </button>
          </header>
          <div class="notice-content"></div>
          <div class="pull-right" id="defineWrapPaginationOfNotice"></div>
        </div> -->
        <!-- start notice -->

        <div class="tab-pane brands" id="edit_brands">
          <div class="tab-pane brandsPages"></div>

          <div class="brand-panel hidden"></div>
          <div class="brands-pop hidden">
            <h2>
                <span>选择品牌</span>
                <a class="fa fa-times" data-id="close" title="关闭"></a>
            </h2>
            <div class="selectShoping">
              <p><input type="text" name="" value="" placeholder="请输入品牌名称"><i class="fa fa-search"></i></p>
              <div class="box-nav">
                <table>
                  <thead>
                    <tr><td>品牌</td><td>名称</td><td>所属供应商</td></tr>
                  </thead>
                </table>
              </div>
              <div class="box"></div>
              <p><input type="button" name="" value="确认提交" id="create-select-shoping-submit"></p>
            </div>
          </div>

          <div class="edit-brands-pop hidden">
            <h2>
                <span>编辑品牌信息</span>
                <a class="fa fa-times" data-id="close" title="关闭"></a>
            </h2>
            <div class="editShoping">
              <div class="row">
                <span>品牌名称：</span>
                <div>
                  <span data-edit-name></span>
                  <span data-change='reSelect'>更改品牌</span>
                </div>
              </div>
              <div class="row">
                <span>供应商：</span>
                <div>
                  <span data-edit-supply></span>
                </div>
              </div>
              <div class="row">
                <span>原logo：</span>
                <div>
                  <span data-edit-img=""><img src="" class="img-origin"></span>
                </div>
              </div>
              <div class="row">
                <span>展示logo：</span>
                <div>
                  <span>
                    <label class="img-upload-box" for="upload_img_input_brands">
                      <input type="file" name="" class="file-upload" id="upload_img_input_brands">
                    </label>
                    <small class="marks">备:<abbr>146</abbr>*<abbr>80</abbr></small>
                  </span>
                </div>
              </div>
              <p><input type="button" name="" value="提交" id="edit-select-shoping-submit"></p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
<!-- 图片相关信息 -->
<div class="apx-modal-admin-brand-img modal fade" id="apxModalAdminBrandImg" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></a>
                <h4 class="modal-title">图片相关信息</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <!--图片-->
                    <div class="form-group">
                        <label class="col-xs-3 control-label">图片：</label>
                        <div class="col-xs-3">
                            <label class="upload-box" for="brand_img">
                                <input id="brand_img" type="file">
                                <img src="">
                            </label>
                        </div>
                        <div class="col-xs-6 text-danger">（图片尺寸要求：<span class="J_img_pixel"></span>）</div>
                    </div>
                    <!--链接-->
                    <div class="form-group">
                        <label for="brand_url" class="col-xs-3 control-label">对应链接：</label>
                        <div class="col-xs-7">
                            <input type="text" class="form-control" id="brand_url" >
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-block" id="J_brand_img_upload">提交</button>
            </div>
        </div>
    </div>
</div>

<!-- editor script -->
<script src="/vender/kindeditor/kindeditor-all-min.js"></script>
<script>
  // kindeditor
  window.KindEditor && KindEditor.ready(function(K) {
      window.editor = K.create('#apx_editor',{
          items: [
              'source', 'preview', '|', 'undo', 'redo', '|', 'justifyleft', 'justifycenter', 'justifyright',
              'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
              'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
              'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
              'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image',
              'flash', 'link'
          ]
      });
  });
</script>
