<?php
$this->params = ['css' => 'css/i.css', 'js' => 'js/i.js'];
?>
<div class="video-container">
    <div id="youkuplayer" style="width: 960px;height:520px;margin: 20px auto;"></div>
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