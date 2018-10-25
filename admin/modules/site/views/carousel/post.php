<?php
/**
 * @var $this \yii\web\View
 */
$this->title = '' ;
$asset = \admin\modules\site\assets\SiteAsset::register($this);
$asset->js[] = ['js/site-index.js', 'js/notice-adding.js'];
$asset->css[] = ['css/site-index.css', 'css/notice-adding.css'];
?>

<div class="childrenPages">
	<div class='choice-unit'>
		<p>
			<em>类型：</em>
		 	<span class="tab-active" data-toggle="tab" id="unit-web">网站公告</span>
		 	<span class="" data-toggle="tab" id="unit-domain">外部链接</span>
		</p>

		<div class="unit-content">
			<p>
			 	<em>图片：</em>
			 	<i class="add-picture">
			 		<label class="img-upload-box" for="upload_img_input">
			 			<input type="file" name="" class="file-upload" id="upload_img_input">
			 		</label>
			 	</i>
			 			<sup>备:<abbr>230</abbr>*<abbr>152</abbr></sup>
			</p>
			<p>
				<em>标题：</em>
				<input type="text" class="alone-row title-txt addtitle" placeholder="不能超过30个字符"/>
			</p>
			<p>
				<em>内容：</em>
			  <i class="col-xs-6 text-center">
			  	<textarea id='apx_editor' name='content' style='width: 320px;height: 340px'></textarea>
			  </i>
			  <input type="text" name="" class="alone-row link-txt hidden">
			</p>
		</div>
	</div>
</div>

<div class="footer-unit">
	<input type="button" value="取消" class="clear-btn">
	<input type="button" value="提交" class="submit-btn btn-active" data-event='unit-web'>
</div>

<script src='/vender/kindeditor/kindeditor-all-min.js'></script>
<script type="text/javascript">
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
      })
  })
</script>
<script type="text/javascript">


	// console.log(spt)
	// $('.img-upload-box').css('background-image','url('+data.url+')')
	// $('.title-txt').val()
	// editor.html('')
	// $('.link-txt').val()

</script>