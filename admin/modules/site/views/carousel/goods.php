<?php
$this->params = ['js' => ['js/site-index.js', 'js/add-new-floor.js'], 'css' => ['css/site-index.css', 'css/add-new-floor.css']];
?>

<div class="admin-frame-main">
  <div class="admin-main-wrap">
    <div class="admin-edit-index wrapper-for-new-floor">
      <header>
        <h1>添加楼层</h1>
      </header>
      <ul>
        <li>
          <label>类型：</label>
          <div>
            <label for="pc">
              <input id="pc" type="radio" name="floorType" value="1" checked="checked" /><span>pc</span>
            </label>
            <label for="wap">
              <input id="wap" type="radio" name="floorType" value="2" /><span>wap</span>
            </label>
          </div>
        </li>
        <li>
          <label>名称：</label>
          <div>
            <input type="text" data-id="name" maxlength="30" />
          </div>
        </li>
        <li>
          <label>链接：</label>
          <div>
            <input type="text" data-id="url" maxlength="200" />
          </div>
        </li>
        <li>
          <label>楼层主色：</label>
          <div>
            <input type="text" data-id="color" />
          </div>
        </li>
        <li>
          <label>商品组：</label>
          <div>
            <div data-id="groupContent"></div>
            <button class="button primary-o" data-id="addNewGroup">增加一组商品</button>
          </div>
        </li>
      </ul>
      <footer>
        <button class="button primary" data-id="submit">提交</button><button class="button" data-id="cancel">取消</button>
      </footer>

    </div>
  </div>
</div>
