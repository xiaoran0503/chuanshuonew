<?php if (!defined('__ROOT_DIR__')) exit;?>

<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="UTF-8">
<title><?=$author?> 的全部作品_<?=SITE_NAME?></title>
<meta name="keywords" content="<?=$author?>的全部小说">
<meta name="description" content="<?=$author?>的全部小说">
<?php require_once 'tpl_header.php'; ?>

<div class="container flex flex-wrap mb20">
    <div class="border3 commend flex flex-between category-commend">
        <?php if(is_array($res)): ?><?php foreach($res as $k => $v): ?>	
            <div class="category-div">
                <a href="<?=$v['info_url']?>"><img class="lazy" src="/static/<?=$theme_dir?>/nocover.jpg" data-original="<?=$v['img_url']?>"></a>
                <div>
                    <div class="flex flex-between commend-title"><a href="<?=$v['info_url']?>"><h3><?=$v['articlename']?></h3></a> <span><?=$v['author']?></span></div>
                    <div class="intro indent"><?=$v['intro_des']?></div>
                </div>
            </div>
        <?php endforeach ?><?php endif?>
    </div>
</div>

<?php require_once 'tpl_footer.php'; ?>
