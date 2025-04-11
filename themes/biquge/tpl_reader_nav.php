<?php
// 定义一个安全输出函数，对变量进行 HTML 实体转义
function safeEcho($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<div class="read_nav <?php if(isset($class)) echo $class; ?>">
    <!-- 上一页/上一章 -->
    <?php if(safeEcho($prevpage_url) != ''): ?>
        <a id="prev_url" href="<?= safeEcho($prevpage_url) ?>"><i class="fa fa-backward"></i> 上一页</a>
    <?php else: ?>
        <?php if(safeEcho($pre_cid) == 0): ?>
            <a id="prev_url" href="javascript:void(0);" class="w_gray"><i class="fa fa-stop"></i> 书首页</a>
        <?php else: ?>
            <a id="prev_url" href="<?= safeEcho($pre_url) ?>"><i class="fa fa-backward"></i> 上一章</a>
        <?php endif ?>
    <?php endif ?>
    <!-- 返回目录 -->
    &nbsp; ← &nbsp;
    <a id="info_url" href="<?= safeEcho($info_url) ?>"  disable="disabled">章节目录</a>
    &nbsp; → &nbsp;
    <!-- 下一页/下一章 -->
    <?php if(safeEcho($nextpage_url) != ''): ?>
        <a id="next_url" href="<?= safeEcho($nextpage_url) ?>"><i class="fa fa-forward"></i> 下一页</a>
    <?php else: ?>
        <?php if(safeEcho($next_cid) == 0): ?>
            <a id="next_url" href="javascript:void(0);" class="w_gray">书末页 <i class="fa fa-stop"></i></a>
        <?php else: ?>
            <a id="next_url" href="<?= safeEcho($next_url) ?>">下一章 <i class="fa fa-forward"></i></a>
        <?php endif ?>
    <?php endif ?>
</div>