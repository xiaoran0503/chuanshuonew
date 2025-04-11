<?php
// 防止直接访问该文件
if (!defined('__ROOT_DIR__')) {
    exit;
}
?>
<!-- header -->
<meta http-equiv="Cache-Control" content="no-siteapp" />
<meta http-equiv="Cache-Control" content="no-transform" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no, user-scalable=no">
<meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1">

<!-- 对文件路径进行安全转义 -->
<link rel="shortcut icon" type="image/x-icon" href="<?= htmlspecialchars('/static/' . $theme_dir . '/favicon.ico', ENT_QUOTES, 'UTF-8') ?>" media="screen">
<link rel="stylesheet" href="<?= htmlspecialchars('/static/' . $theme_dir . '/style.css', ENT_QUOTES, 'UTF-8') ?>">
<script src="//cdn.staticfile.org/jquery/3.4.0/jquery.min.js"></script>
<script src="//cdn.staticfile.org/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="//cdn.staticfile.org/jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>
<script src="<?= htmlspecialchars('/static/' . $theme_dir . '/common.js', ENT_QUOTES, 'UTF-8') ?>"></script>
</head>

<body>
<header>
    <!-- 对站点名称和 URL 进行安全转义 -->
    <a href="/"><div class="logo"><em><?= htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8') ?></em><?= htmlspecialchars(SITE_URL, ENT_QUOTES, 'UTF-8') ?></div></a>
    <div class="diswap">
        <button id="menu-btn" type="text" onclick="javascript:menu_toggle();" class="search_btn">菜单</button>
    </div>
    <!-- 搜索框 -->
    <?php
    // 检查 search 函数是否存在
    if (function_exists('search')) {
        search();
    }
    ?>
</header>
<nav class="dispc">
    <a href="/">首页</a>
    <?php
    // 检查 Sort::ss_sorthead 函数是否存在
    if (method_exists('Sort', 'ss_sorthead')) {
        foreach (Sort::ss_sorthead() as $k => $v) {
            // 对链接和分类名进行安全转义
            echo '<a href="'. htmlspecialchars($v['sorturl'], ENT_QUOTES, 'UTF-8') .'">'. htmlspecialchars($v['sortname'], ENT_QUOTES, 'UTF-8') .'</a>';
        }
    }
    ?>
    <a href="/recentread/">阅读记录</a>
</nav>