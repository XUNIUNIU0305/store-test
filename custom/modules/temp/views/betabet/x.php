<?php
$this->params = ['css' => 'css/x2.css', 'js' => 'js/x2.js'];
?>
<div class="wrajpg">
    <div class="bannertop">
        <div class="times">00</div>
    </div>
    <div class="titleone">
        <ul class="pro-list">
            <li><a href="/product?id=2417"></a></li>
            <li><a href="/product?id=2417"></a></li>
            <li><a href="/product?id=2417"></a></li>
            <li><a href="/product?id=2417"></a></li>
            <li><a href="/product?id=2414"></a></li>
            <li><a href="/product?id=2414"></a></li>
        </ul>
    </div>
    <div class="titleone2">
        <a href="/temp/betabet/h"></a>
        <a href="/temp/betabet/y"></a>
    </div>
    <div class="titleone3">
        <ul class="pro" id="container"></ul>
        <script id="ProList" type="text/template">
            {@each data as it,index}
                <li class="prolist">
                    <div class="pic"><img src="${it.main_image}" alt=""></div>
                    <div class="center">
                        <div class="protitle">${it.title}</div>
                        <!-- <div class="protuijian">推荐：<span>${it.description}</span></div> -->
                        <div class="protese">特色：<span>${it.description}</span></div>
                        <div class="promail">￥<span class="promailnum">${it.price.min}</span><a href="/product?id=${it.id}"><span class="promailbtn"></span></a></div>
                    </div>
                </li>
            {@/each}
        </script>
        <div class="histop"></div>
    </div>
</div>