<?php
$this->params = ['css' => 'css/u.css', 'js' => 'js/u.js'];

$this->title = '视频';
?>
<div class="video-container">
    <div id="youkuplayer" style="width: 100%;height:200px;margin: 20px auto;"></div>
</div>
<script type="text/javascript" src="//player.youku.com/jsapi"></script>
<script type="text/javascript">
    var player = new YKU.Player('youkuplayer',{
        styleid: '0',
        client_id: '73c46fdbebb30e76',
        vid: 'XMzI1MDUzMDE5Ng',
        newPlayer: true
    });
</script>