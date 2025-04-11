<?php if (!defined('__ROOT_DIR__')) exit; ?>
<!-- header -->
<meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta http-equiv="Cache-Control" content="no-transform" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no, user-scalable=no">
<meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1">

    <link rel="shortcut icon" type="image/x-icon" href="/static/<?=$theme_dir?>/favicon.ico" media="screen">
    <link rel="stylesheet" href="/static/<?=$theme_dir?>/style.css">
    <script src="//cdn.staticfile.org/jquery/3.4.0/jquery.min.js"></script>
    <script src="//cdn.staticfile.org/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
    <script src="//cdn.staticfile.org/jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>
    <script src="/static/<?=$theme_dir?>/common.js"></script>
</head>

<body>
<header>
    <a href="/"><div class="logo"><em><?=SITE_NAME?></em><?=SITE_URL?></div></a>
    <div class="diswap">
    <button id="menu-btn" type="text" onclick="javascript:menu_toggle();" class="search_btn">菜单</button>
    </div>
<!-- 搜索框 -->
<div class="search dispc"><script>search();</script></div>
</header>
<nav class="dispc">
    <a href="/">首页</a>
    <?php foreach(Sort::ss_sorthead() as $k => $v): ?>
        <a href="<?=$v['sorturl']?>"><?=$v['sortname']?></a>
    <?php endforeach ?>
    <a href="/recentread/">阅读记录</a>
</nav>



