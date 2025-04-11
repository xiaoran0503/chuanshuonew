<?php if (!defined('__ROOT_DIR__')) exit; ?>

<!DOCTYPE html>
<html lang='zh'>
<head>
<meta charset="UTF-8">
    <title> <?=$chaptername?>_<?=$articlename?>_<?=SITE_NAME?></title>
    <meta name="keywords" content="<?=$articlename?>, <?=$chaptername?>" />
    <meta name="description" content="<?=SITE_NAME?>提供了<?=$author?>创作的<?=$sortname?>小说《<?=$articlename?>》干净清爽无错字的文字章节： <?=$chaptername?>在线阅读。" />

<?php require_once 'tpl_header.php'; require_once __ROOT_DIR__ .'/shipsay/include/neighbor.php';?>
<div class="container">
    <div class="border3-2" id="ss-reader-main">
        <div class="info-title">
            <a href="/"><?=SITE_NAME?></a> &gt; <a href="<?=Sort::ss_sorturl($sortid)?>"><?=$sortname?></a> &gt; <a href="<?=$info_url?>"><?=$articlename?></a> &gt; <?=$chaptername?>
        </div>
        <div class="reader-main">
            <script src="/static/<?=$theme_dir?>/readpage.js"></script>
            <h1><?=$chaptername?>（<?=$now_pid?> / <?=$max_pid?>）</h1>

            <!-- 控制按钮 -->
            <div class="read_nav">
                <!-- 上一页/上一章 -->
                <?php if($prevpage_url != ''): ?>
                    <a id="prev_url" href="<?=$prevpage_url?>"><i class="fa fa-backward"></i> 上一页</a>
                <?php else: ?>
                    <?php if($pre_cid == 0): ?><a id="pre_url" href="javascript:void(0);" class="w_gray"><i class="fa fa-stop"></i> 书首页</a><?php else: ?><a id="prev_url" href="<?=$pre_url?>"><i class="fa fa-backward"></i> 上一章</a><?php endif ?>
                <?php endif ?>
                <!-- 返回目录 -->
                &nbsp; ← &nbsp;<a id="info_url" href="<?=$info_url?>"  disable="disabled">章节目录</a>&nbsp; → &nbsp;
                <!-- 下一页/下一章 -->
                <?php if($nextpage_url != ''): ?>
                    <a id="next_url" href="<?=$nextpage_url?>"><i class="fa fa-forward"></i> 下一页</a>
                <?php else: ?>
                    <?php if($next_cid == 0): ?><a id="next_url" href="javascript:void(0);" class="w_gray">书末页 <i class="fa fa-stop"></i></a><?php else: ?><a id="next_url" href="<?=$next_url ?>">下一章 <i class="fa fa-forward"></i></a><?php endif ?>
                <?php endif ?>
            </div>
            <!-- /控制按钮 -->

        </div>

        <div class="info-commend mt8">推荐阅读: 
            <?php foreach($neighbor as $k => $v): ?>
                <a href="<?=$v['info_url'] ?>" title="<?=$articlename?>"><?=$v['articlename'] ?></a>
            <?php endforeach ?>
        </div>
        <div class="reader-hr" ></div>
        <!-- 正文部分 -->
        <article id="article" class="content"><?=$rico_content?></article>
        <div class="reader-hr" ></div>
        <!-- 控制按钮 -->
        <div class="read_nav reader-bottom">
            <!-- 上一页/上一章 -->
            <?php if($prevpage_url != ''): ?>
                <a id="prev_url" href="<?=$prevpage_url?>"><i class="fa fa-backward"></i> 上一页</a>
            <?php else: ?>
                <?php if($pre_cid == 0): ?><a id="prev_url" href="javascript:void(0);" class="w_gray"><i class="fa fa-stop"></i> 书首页</a><?php else: ?><a id="prev_url" href="<?=$pre_url?>"><i class="fa fa-backward"></i> 上一章</a><?php endif ?>
            <?php endif ?>
            <!-- 返回目录 -->
            &nbsp; ← &nbsp;<a id="info_url" href="<?=$info_url?>"  disable="disabled">章节目录</a>&nbsp; → &nbsp;
            <!-- 下一页/下一章 -->
            <?php if($nextpage_url != ''): ?>
                <a id="next_url" href="<?=$nextpage_url?>"><i class="fa fa-forward"></i> 下一页</a>
            <?php else: ?>
                <?php if($next_cid == 0): ?><a id="next_url" href="javascript:void(0);" class="w_gray">书末页 <i class="fa fa-stop"></i></a><?php else: ?><a id="next_url" href="<?=$next_url ?>">下一章 <i class="fa fa-forward"></i></a><?php endif ?>
            <?php endif ?>
        </div>
        <!-- /控制按钮 -->


    </div>
</div>

<div class="container">
    <div class="info-commend mt8">最新小说: 
        <?php foreach($postdate as $k => $v): ?>
            <a href="<?=$v['info_url'] ?>" title="<?=$articlename?>"><?=$v['articlename'] ?></a>
        <?php endforeach ?>
    </div>
</div>

<script src="/static/<?=$theme_dir?>/tempbookcase.js"></script>
<script>
    lastread.set('<?=$articleid?>','<?=$uri?>','<?=$articlename?>','<?=$chaptername?>','<?=$author?>','<?=date('Y-m-d', time())?>');
    const preview_page = $('#prev_url').attr('href');
    const index_page = $('#info_url').attr('href');
    const next_page = $('#next_url').attr('href');
    function jumpPage(){
        const event = document.all ? window.event : arguments[0];
        if (event.keyCode == 37) document.location = preview_page;
        if (event.keyCode == 39) document.location = next_page;
        if (event.keyCode == 13) document.location = index_page;
    }
    document.onkeydown = jumpPage;
</script>
<?php require_once 'tpl_footer.php'; ?>
