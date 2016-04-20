<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>跳转提示 - bjyadmin</title>
    <bootstrapcss />
</head>
<body>
<!-- 引入顶部导航开始 -->
<include file="@Public/public_nav_fwb" />
<!-- 引入顶部导航结束 -->

<!-- 引入搜索开始 -->
<include file="@Public/public_search_fwb" />
<!-- 引入搜索结束 -->
<div class="xb-h-100"></div>
<div class="xb-out">
    <ul class="bjy-public-jump">
        <li class="bjy-pj-word">
            <b>{$message}{$error}</b>
        </li>
        <li class="bjy-pj-word">
            页面将在<b id="wait">{$waitSecond}</b>秒后<a id="href" href="{$jumpUrl}">跳转</a>
        </li>
    </ul>
</div>
<div class="xb-h-100"></div>

<!-- 引入底部开始 -->
<include file="@Public/index_foot_fwb" />
<include file="@Public/public_foot_fwb" />
<!-- 引入底部结束 -->

<bootstrapjs />
<script type="text/javascript">
(function(){
    var wait = document.getElementById('wait'),href = document.getElementById('href').href;
    var interval = setInterval(function(){
        var time = --wait.innerHTML;
        if(time <= 0) {
            location.href = href;
            clearInterval(interval);
        };
    }, 1000);
})();
</script>
</body>
</html>