<?php header("Cache-Control: no-store, no-cache, must-revalidate");
if(session_status()!==PHP_SESSION_ACTIVE)session_start();
define('__ROOT_DIR__',str_replace('\\','/',dirname(dirname(__DIR__))));
$admin_url=explode('/',$_SERVER['REQUEST_URI'])[1];
require_once __ROOT_DIR__.'/shipsay/version.php';
$has_admin_folder=false;
$www_dir=opendir(__ROOT_DIR__.'/www');
while($wwwfile=readdir($www_dir))
{
	if(strtolower($wwwfile)=='admin')$has_admin_folder=true;
}
?>

<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
   <title>Admin - 船说CMS - ShipSay.com</title>
   <link rel="stylesheet" href="/layui/css/layui.css">
   <script src="/layui/layui.js"></script>
   <script src="//cdn.staticfile.org/jquery/3.4.0/jquery.min.js"></script>
</head>

<body class="layui-layout-body">
   <div class="layui-layout layui-layout-admin">

       <div class="layui-header">
           <div class="layui-logo">
               船说CMS <span class="layui-badge"><?=$ShipSayVersion['Ver']?></span>
           </div>
           <ul class="layui-nav layui-layout-left">
               <li class="layui-nav-item"><a target="_blank" href="<?=$ShipSayVersion['Site']?>"><i class="layui-icon layui-icon-home"></i> <?=$ShipSayVersion['Site']?></a></li>
               <li class="layui-nav-item"><a target="_blank" href="<?=$ShipSayVersion['QQ']['url']?>">QQ群: <?=$ShipSayVersion['QQ']['group']?></a></li>
               <li class="layui-nav-item"><a href="javascript:void(0)"><?php if($has_admin_folder): ?><b class="layui-bg-red" style="padding:0 5px;margin-right:5px;">安全警告:</b>存在默认后台文件夹"admin",请改名或删除<?php else: ?>请定期修改后台文件夹名<?php endif ?></a></li>
           </ul>

           <ul class="layui-nav layui-layout-right">
               <!-- <li class="layui-nav-item"><?=$_SESSION['ss_username']?></li>
     <li class="layui-nav-item"><a href="javascript:changepass();">改密</a></li>
     <li class="layui-nav-item"><a href="/logout/">退出</a></li> -->
               <li class="layui-nav-item"><a href="/" target="_blank">网站首页</a></li>
           </ul>
       </div>

       <div class="layui-side layui-bg-black">
           <div class="layui-side-scroll">
               <ul class="layui-nav layui-nav-tree">
                   <li class="layui-nav-item"><a href="/<?=$admin_url?>/main.php"><i class="layui-icon layui-icon-home"></i> 概述</a></li>
                   <li class="layui-nav-item"><a href="/<?=$admin_url?>/base/"><i class="layui-icon layui-icon-set"></i> 参数设置</a></li>
                   <li class="layui-nav-item"><a href="/<?=$admin_url?>/article/"><i class="layui-icon layui-icon-read"></i> 小说管理</a></li>
                   <li class="layui-nav-item"><a href="/<?=$admin_url?>/report/"><i class="layui-icon layui-icon-survey"></i> 章节报错</a></li>
                   <li class="layui-nav-item"><a href="/<?=$admin_url?>/filter/"><i class="layui-icon layui-icon-vercode"></i> 过滤替换</a></li>
                   <li class="layui-nav-item"><a href="/<?=$admin_url?>/link/"><i class="layui-icon layui-icon-link"></i> 友情链接</a></li>
                   <li class="layui-nav-item"><a href="/<?=$admin_url?>/search/"><i class="layui-icon layui-icon-search"></i> 搜索模块</a></li>
                   <li class="layui-nav-item"><a href="/<?=$admin_url?>/count/"><i class="layui-icon layui-icon-list"></i> 统计代码</a></li>
                   <li class="layui-nav-item"><a href="/<?=$admin_url?>/app/"><i class="layui-icon layui-icon-android"></i> App设置</a></li>
               </ul>
           </div>
       </div>

       <script>
           const url = location.href;
           if (url.match(/main/i)) {
               $('.layui-nav-tree > li:eq(0)').addClass('layui-this');
           } else if (url.match(/base/i)) {
               $('.layui-nav-tree > li:eq(1)').addClass('layui-this');
           } else if (url.match(/article/i)) {
               $('.layui-nav-tree > li:eq(2)').addClass('layui-this');
           } else if (url.match(/report/i)) {
               $('.layui-nav-tree > li:eq(3)').addClass('layui-this');
           } else if (url.match(/filter/i)) {
               $('.layui-nav-tree > li:eq(4)').addClass('layui-this');
           } else if (url.match(/link/i)) {
               $('.layui-nav-tree > li:eq(5)').addClass('layui-this');
           } else if (url.match(/search/i)) {
               $('.layui-nav-tree > li:eq(6)').addClass('layui-this');
           } else if (url.match(/count/i)) {
               $('.layui-nav-tree > li:eq(7)').addClass('layui-this');
           } else if (url.match(/app/i)) {
               $('.layui-nav-tree > li:eq(8)').addClass('layui-this');
           }
           /*
   function changepass(){
       layer.open({
           type: 2,
           title: '修改 <?=$_SESSION['ss_username']?> 密码',
           area: ['50%', '50%'],
           content: '/<?=$admin_url?>/include/tpl_changepass.php'
       })
   }
*/
       </script>