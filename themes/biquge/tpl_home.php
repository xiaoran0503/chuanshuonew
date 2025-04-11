<?php if (!defined('__ROOT_DIR__')) exit; ?>

<!DOCTYPE html>
<html lang='zh'>
<head>
<meta charset="UTF-8">
    <title><?=SITE_NAME?>_无弹窗书友最值得收藏的网络小说阅读网</title>
    <meta name="keywords" content="<?=SITE_NAME?>,无弹窗,小说阅读网,<?=SITE_URL?>" />
    <meta name="description" content="<?=SITE_NAME?>是广大书友最值得收藏的网络小说阅读网，网站收录了当前最火热的网络小说，免费提供高质量的小说最新章节，是广大网络小说爱好者必备的小说阅读网。" />

<?php require_once 'tpl_header.php'; ?>

<div class="container flex flex-wrap">
    <!-- 封推 -->
    <div class="border3 commend flex flex-between">
        <?php foreach($commend as $k => $v): ?><?php if($k < 4):?>
            <div class="outdiv">
                <a href="<?=$v['info_url']?>"><img class="lazy" src="/static/<?=$theme_dir?>/nocover.jpg" data-original="<?=$v['img_url']?>"></a>
                <div>
                    <div class="flex flex-between commend-title"><a href="<?=$v['info_url']?>"><h3><?=$v['articlename']?></h3></a> <span><?=$v['author']?></span></div>
                    <div class="intro indent"><?=$v['intro_des']?></div>
                </div>
            </div>
        <?php endif ?><?php endforeach ?>
    </div>
    <!-- 热门 -->
    <div class="border3 popular">
        <p>经典推荐</p>
        <?php foreach($popular as $k => $v): ?><?php if($k < 8):?>
            <div class="list-out">
                <span>[<?=$v['sortname_2']?>] <a href="<?=$v['info_url']?>"><?=$v['articlename']?></a></span>
                <span class="gray"><?=$v['author']?></span>
            </div>
        <?php endif ?><?php endforeach ?>
    </div>
</div>

<!-- 中间6区块之一 -->
<div class="container sort-section border3 flex flex-between flex-wrap">
    <!-- 分类1 -->
    <div>
        <div class="sort-title">
            <a href="<?=Sort::ss_sorturl(1)?>"><?=Sort::ss_sortname(1,1)?></a>
        </div>

        <div class="sort-bottom">
            <?php foreach($sort1 as $k => $v): ?><?php if($k == 0):?>

            <div class="sortdiv flex">
            <a href="<?=$v['info_url']?>"><img class="lazy" src="/static/<?=$theme_dir?>/nocover.jpg" data-original="<?=$v['img_url']?>"></a>
                <div>
                    <a href="<?=$v['info_url']?>"><h4><?=$v['articlename']?></h4></a>
                    <p><?=$v['intro_des']?></p>
                </div>
            </div>
            <?php endif ?><?php endforeach ?>
            <div class="sort-section-more flex flex-wrap">
                <?php foreach($sort1 as $k => $v): ?><?php if($k > 0):?>
                <div class="sortlist"><a href="<?=$v['info_url']?>"><?=$v['articlename']?></a><span class="s_gray">/<?=$v['author']?></span></div>
                <?php endif ?><?php endforeach ?>
            </div>

        </div>
    </div>

    <!-- 分类2 -->
    <div class="sort-middle">
        <div class="sort-title">
            <a href="<?=Sort::ss_sorturl(2)?>"><?=Sort::ss_sortname(2,1)?></a>
        </div>

        <div class="sort-bottom">
            <?php foreach($sort2 as $k => $v): ?><?php if($k == 0):?>
                
            <div class="sortdiv flex">
                <a href="<?=$v['info_url']?>"><img class="lazy" src="/static/<?=$theme_dir?>/nocover.jpg" data-original="<?=$v['img_url']?>"></a>
                <div>
                    <a href="<?=$v['info_url']?>"><h4><?=$v['articlename']?></h4></a>
                    <p><?=$v['intro_des']?></p>
                </div>
            </div>
            <?php endif ?><?php endforeach ?>
            <div class="sort-section-more flex flex-wrap">
                <?php foreach($sort2 as $k => $v): ?><?php if($k > 0):?>
                <div class="sortlist"><a href="<?=$v['info_url']?>"><?=$v['articlename']?></a><span class="s_gray">/<?=$v['author']?></span></div>
                <?php endif ?><?php endforeach ?>
            </div>
            
        </div>
    </div>

    <!-- 分类3 -->
    <div>
        <div class="sort-title">
            <a href="<?=Sort::ss_sorturl(3)?>"><?=Sort::ss_sortname(3,1)?></a>
        </div>

        <div class="sort-bottom">
            <?php foreach($sort3 as $k => $v): ?><?php if($k == 0):?>

            <div class="sortdiv flex">
                <a href="<?=$v['info_url']?>"><img class="lazy" src="/static/<?=$theme_dir?>/nocover.jpg" data-original="<?=$v['img_url']?>"></a>
                <div>
                    <a href="<?=$v['info_url']?>"><h4><?=$v['articlename']?></h4></a>
                    <p><?=$v['intro_des']?></p>
                </div>
            </div>
            <?php endif ?><?php endforeach ?>
            <div class="sort-section-more flex flex-wrap">
                <?php foreach($sort3 as $k => $v): ?><?php if($k > 0):?>
                <div class="sortlist"><a href="<?=$v['info_url']?>"><?=$v['articlename']?></a><span class="s_gray">/<?=$v['author']?></span></div>
                <?php endif ?><?php endforeach ?>
            </div>
            
        </div>
    </div>
</div>

<!-- 中间6区块之二 -->
<div class="container sort-section border3 flex flex-between flex-wrap">
    <!-- 分类4 -->
    <div>
        <div class="sort-title">
            <a href="<?=Sort::ss_sorturl(4)?>"><?=Sort::ss_sortname(4,1)?></a>
        </div>

        <div class="sort-bottom">
            <?php foreach($sort4 as $k => $v): ?><?php if($k == 0):?>

            <div class="sortdiv flex">
                <a href="<?=$v['info_url']?>"><img class="lazy" src="/static/<?=$theme_dir?>/nocover.jpg" data-original="<?=$v['img_url']?>"></a>
                <div>
                    <a href="<?=$v['info_url']?>"><h4><?=$v['articlename']?></h4></a>
                    <p><?=$v['intro_des']?></p>
                </div>
            </div>
            <?php endif ?><?php endforeach ?>
            <div class="sort-section-more flex flex-wrap">
                <?php foreach($sort4 as $k => $v): ?><?php if($k > 0):?>
                <div class="sortlist"><a href="<?=$v['info_url']?>"><?=$v['articlename']?></a><span class="s_gray">/<?=$v['author']?></span></div>
                <?php endif ?><?php endforeach ?>                
            </div>
            

        </div>
    </div>

    <!-- 分类5 -->
    <div class="sort-middle">
        <div class="sort-title">
            <a href="<?=Sort::ss_sorturl(5)?>"><?=Sort::ss_sortname(5,1)?></a>
        </div>

        <div class="sort-bottom">
            <?php foreach($sort5 as $k => $v): ?><?php if($k == 0):?>

            <div class="sortdiv flex">
                <a href="<?=$v['info_url']?>"><img class="lazy" src="/static/<?=$theme_dir?>/nocover.jpg" data-original="<?=$v['img_url']?>"></a>
                <div>
                    <a href="<?=$v['info_url']?>"><h4><?=$v['articlename']?></h4></a>
                    <p><?=$v['intro_des']?></p>
                </div>
            </div>
            <?php endif ?><?php endforeach ?>
            <div class="sort-section-more flex flex-wrap">
                <?php foreach($sort5 as $k => $v): ?><?php if($k > 0):?>
                <div class="sortlist"><a href="<?=$v['info_url']?>"><?=$v['articlename']?></a><span class="s_gray">/<?=$v['author']?></span></div>
                <?php endif ?><?php endforeach ?> 
            </div>
        </div>
    </div>

    <!-- 分类6 -->
    <div>
        <div class="sort-title">
            <a href="<?=Sort::ss_sorturl(6)?>"><?=Sort::ss_sortname(6,1)?></a>
        </div>

        <div class="sort-bottom">
            <?php foreach($sort6 as $k => $v): ?><?php if($k == 0):?>

            <div class="sortdiv flex">
                <a href="<?=$v['info_url']?>"><img class="lazy" src="/static/<?=$theme_dir?>/nocover.jpg" data-original="<?=$v['img_url']?>"></a>
                <div>
                    <a href="<?=$v['info_url']?>"><h4><?=$v['articlename']?></h4></a>
                    <p><?=$v['intro_des']?></p>
                </div>
            </div>
            <?php endif ?><?php endforeach ?>
            <div class="sort-section-more flex flex-wrap">
                <?php foreach($sort6 as $k => $v): ?><?php if($k > 0):?>
                <div class="sortlist"><a href="<?=$v['info_url']?>"><?=$v['articlename']?></a><span class="s_gray">/<?=$v['author']?></span></div>
                <?php endif ?><?php endforeach ?> 
            </div>
            

        </div>
    </div>
</div>
<!-- 最后更新和最新入库 -->
<div class="container flex flex-wrap section-bottom">
    <!-- 最后更新 -->
    <div class="border3-1 lastupdate">
        <p>最后更新</p>
        <?php foreach($lastupdate as $k => $v): ?>
            <div class="list-out">
                <span class="flex w80"><em>[<?=$v['sortname']?>]</em><em><a href="<?=$v['info_url']?>"><?=$v['articlename']?></a></em><em><a href="<?=$v['last_url']?>"><?=$v['lastchapter']?></a></em></span>
                <span class="gray dispc"><?=$v['author']?>&nbsp;&nbsp;<?=date('m-d',$v['lastupdate'])?></span>
        </div>
        <?php endforeach ?>
    </div>
    <!-- 最新入库 -->
    <div class="border3-1 popular">
        <p>最新入库</p>
        <?php foreach($postdate as $k => $v): ?>
            <div class="list-out">
                <span>[<?=$v['sortname_2']?>] <a href="<?=$v['info_url']?>"><?=$v['articlename']?></a></span>
                <span class="gray"><?=$v['author']?></span>
            </div>
        <?php endforeach ?>
    </div>
</div>
<!-- 友情链接 -->
<div class="container flex">
    <div class="link">友情链接:</div>
</div>
<script>$('nav a:first-child').addClass('orange');</script>
<?php require_once 'tpl_footer.php'; ?>




