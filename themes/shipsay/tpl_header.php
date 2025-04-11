<?php 
// 确保定义了 __ROOT_DIR__，避免直接访问
if (!defined('__ROOT_DIR__')) {
    exit;
}

// 对需要输出的变量进行 HTML 转义
$siteName = htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8');
$siteUrl = htmlspecialchars(SITE_URL, ENT_QUOTES, 'UTF-8');
$themeDir = htmlspecialchars($theme_dir, ENT_QUOTES, 'UTF-8');
$allBooksUrl = htmlspecialchars($allbooks_url, ENT_QUOTES, 'UTF-8');
$fakeRecentRead = htmlspecialchars($fake_recentread, ENT_QUOTES, 'UTF-8');
?>
<!-- header -->
<meta name="robots" content="all">
<meta name="googlebot" content="all">
<meta name="baiduspider" content="all">
<meta http-equiv="Cache-Control" content="no-siteapp">
<meta http-equiv="Cache-Control" content="no-transform">
<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no, user-scalable=no">
<meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1">
<base target="_self">
<!-- 对图标链接进行转义 -->
<link rel="shortcut icon" type="image/x-icon" href="/static/<?= $themeDir ?>/favicon.ico" media="screen">
<!-- 使用 CDN 加载字体图标样式 -->
<link rel="stylesheet" href="https://cdn.staticfile.org/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- 对样式表链接进行转义 -->
<link rel="stylesheet" href="/static/<?= $themeDir ?>/style.css">
<!-- 使用 CDN 加载 jQuery 及其插件 -->
<script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://cdn.bootcdn.net/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="https://cdn.bootcdn.net/ajax/libs/jquery-lazyload/1.9.1/jquery.lazyload.min.js"></script>
<!-- 对自定义脚本链接进行转义 -->
<script src="/static/<?= $themeDir ?>/common.js"></script>
<!-- 兼容旧版 IE -->
<!--[if lt IE 9]> 
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script> 
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script> 
<![endif]-->
</head>

<body>
    <header>
        <div class="container head">
            <!-- 使用转义后的变量 -->
            <a id="logo" href="/"><span><?= $siteName ?></span>
                <p><?= $siteUrl ?></p>
            </a>
            <!-- 调用搜索函数 -->
            <script>search();</script>
            <div class="header_right">
                <!-- 对链接和文本进行转义 -->
                <a id="home" href="/"><i class="fa fa-home fa-lg"></i><br>首页</a>
                <!-- <a href="javascript:;" onclick="newmessage();" rel="nofollow"><i class="fa fa-pencil-square fa-lg"></i><br>留言</a> -->
                <!-- <a href="/bookcase/" rel="nofollow"><i class="fa fa-tags fa-lg"></i><br>书架</a> -->
                <a href="<?= $allBooksUrl ?>"><i class="fa fa-book fa-lg"></i><br>书库</a>
                <a href="/quanben<?= $allBooksUrl ?>"><i class="fa fa-coffee fa-lg"></i><br>完本</a>
                <a href="<?= $fakeRecentRead ?>" rel="nofollow"><i class="fa fa-history fa-lg"></i><br>足迹</a>
            </div>
        </div>
    </header>
    <div class="navigation">
        <nav class="container">
            <!-- 对链接和文本进行转义 -->
            <a href="/">首页</a>
            <?php 
            // 获取分类头部信息
            $sortHead = Sort::ss_sorthead();
            foreach ($sortHead as $v): 
                $sortUrl = htmlspecialchars($v['sorturl'], ENT_QUOTES, 'UTF-8');
                $sortName2 = htmlspecialchars($v['sortname_2'], ENT_QUOTES, 'UTF-8');
            ?>
                <a href="<?= $sortUrl ?>"><?= $sortName2 ?></a>
            <?php endforeach; ?>
            <div id="user_panel">
                <!-- <script>logininfo();</script>-->
            </div> 
        </nav>
    </div>
    <!-- /header -->