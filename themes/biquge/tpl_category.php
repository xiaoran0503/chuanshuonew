<?php if (!defined('__ROOT_DIR__')) exit; ?>

<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="UTF-8">
	<title><?php if($sortname !=''):?><?=$sortname?>_好看的<?=$sortname?>_<?=$year?><?=$sortname?>小说排行榜<?php else:?>小说书库<?php endif ?>_<?=SITE_NAME?></title>
	<meta name="keywords" content="<?=$sortname?>,好看的<?=$sortname?>,<?=$year?><?=$sortname?>排行榜" />
	<meta name="description" content="<?=SITE_NAME?>是广大书友最值得收藏的<?=$sortname?>阅读网，网站收录了当前最好看的<?=$sortname?>，免费提供高质量的<?=$year?><?=$sortname?>排行榜，是广大<?=$sortname?>爱好者必备的小说阅读网。" />

<?php require_once 'tpl_header.php'; ?>

<div class="container flex flex-wrap">
    <!-- 带封面六本 -->
    <div class="border3 commend flex flex-between category-commend">
        <?php foreach($retarr as $k => $v): ?><?php if($k < 6):?>
            <div class="category-div">
                <a href="<?=$v['info_url']?>"><img class="lazy" src="/static/<?=$theme_dir?>/nocover.jpg" data-original="<?=$v['img_url']?>"></a>
                <div>
                    <div class="flex flex-between commend-title"><a href="<?=$v['info_url']?>"><h3><?=$v['articlename']?></h3></a> <span><?=$v['author']?></span></div>
                    <div class="intro indent"><?=$v['intro_des']?></div>
                </div>
            </div>
        <?php endif ?><?php endforeach ?>
    </div>
</div>

<!-- 最后更新和最新入库 -->
<div class="container flex flex-wrap section-bottom mb20">
    <!-- 最后更新 -->
    <div class="border3-1 lastupdate">
    <p>最后更新的<?=$sortname?>小说</p>
        <?php foreach($retarr as $k => $v): ?><?php if($k >= 6):?>
            <div class="list-out">
                <span class="flex w80"><em>[<?=$v['sortname']?>]</em><em><a href="<?=$v['info_url']?>"><?=$v['articlename']?></a></em><em><a href="<?=$v['last_url']?>"><?=$v['lastchapter']?></a></em></span>
                <span class="gray dispc"><?=$v['author']?>&nbsp;&nbsp;<?=date('m-d',$v['lastupdate'])?></span>
        </div>
        <?php endif ?><?php endforeach ?>
    </div>

    <!-- 最新入库 -->
    <div class="border3-1 popular">
        <p>最新<?=$sortname?>小说</p>
		<?php foreach($sort_postdate as $k => $v): ?>
			<?php if($k < (count($sort_postdate) - 6)):?>
                <div class="list-out">
                    <span>[<?=$v['sortname_2']?>] <a href="<?=$v['info_url']?>"><?=$v['articlename']?></a></span>
					<span class="gray"><?=$v['author']?></span>
            </div>
			<?php endif ?>
		<?php endforeach ?>
    </div>
</div>
<script>$('nav a:nth-child(<?=$sortid + 1?>)').addClass('orange');</script>
<?php require_once 'tpl_footer.php'; ?>