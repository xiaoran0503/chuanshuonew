<?php 
if (!defined('__ROOT_DIR__')) exit; 

// 定义安全输出函数
function safeEcho($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <!-- 安全转义输出的变量 -->
    <title>最近阅读_<?= safeEcho(SITE_NAME) ?></title>
    <?php require_once 'tpl_header.php'; ?>
    <style>
        .recentread-p{
            background-color: #e1eced;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
            font-weight: 700;
            height: 35px;
            overflow: hidden;
            line-height: 35px;
            padding-left: 10px;
        }

        .recentread-main {
            width: 100%;
            display: flex;
            border-bottom: 1px solid #ddd;
            line-height: 40px;
            height: 40px;
            overflow: hidden;
            padding: 0 10px;
            font-size: 13px;
        }
        .recentread-main span{
            display: block;
        }
        .recentread-main > a:first-child > * {
            /* display: block; */
        }

        .recentread-main > a:first-child{
            width: 100%;
            display: flex;
        }	
        .recentread-main > a:last-child{
            width: 40px;
            text-align: right;
            margin-right: 10px;
            color: red;
        }	
        #tempBookcase > a:last-child{width:10%;display:inline}

        .recentread-main span:first-child{width:2%}
        .recentread-main span:nth-child(2){width:25%}
        .recentread-main span:nth-child(3){width:45%}
        .recentread-main span:nth-child(4){width:15%}
        .recentread-main span:nth-child(5){width:13%}

        @media screen and (max-width:767px){
            .recentread-main span:first-child,
            .recentread-main span:nth-child(4),
            .recentread-main span:nth-child(5){
                display: none;
            }
            .recentread-main span:nth-child(2){width:40%}
            .recentread-main span:nth-child(3){width:60%}
        }

    </style>
</head>
<body>
    <div class="container mb20">
        <div class="border3">
            <p class="recentread-p">临时书架 - 用户浏览过的小说会自动保存到书架中（只限同一电脑）</p>
            <div class="recentread-main" style="font-weight: 700;">
                <a>
                    <span></span>
                    <span>书名</span>
                    <span>已读到</span>
                    <span>作者</span>
                    <span>最后阅读</span>
                </a>
                <a>操作</a>
            </div>
            <div id="tempBookcase"></div>
        </div>
        <!-- 安全转义输出的变量 -->
        <script src="/static/<?= safeEcho($theme_dir) ?>/tempbookcase.js"></script>
        <script>
            showtempbooks();
            $("#del_temp").on('click',function(){return false;});
            $('nav a:last-child').addClass('orange');
        </script>
    </div>
    <?php require_once 'tpl_footer.php'; ?>
</body>
</html>