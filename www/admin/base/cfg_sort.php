<?php $tmpvar=file_get_contents($config_file);
preg_match('#//分类设置([\s\S]+)//redis缓存设置#is',$tmpvar,$matches);
?>

<fieldset class="layui-elem-field layui-field-title"><legend>小说分类设置</legend></fieldset>

<div class="layui-form-item layui-form-text">
     <textarea id="sortarr" class="layui-textarea" style="min-height:250px"><?=trim($matches[1])?></textarea>
</div>

<blockquote class="layui-elem-quote layui-text">
 格式: <b style="color:#FF5722">$sortarr[分类id] = ['code' => '分类拼音', 'caption' => '分类名字'];</b><br/><br/>
 示例: $sortarr[1] = ['code' => 'xuanhuan', 'caption' => '玄幻魔法'];<br/><br/>
 释义: 将数据库中分类为 1 的小说, 设定它们在本站的拼音名为: 'xuanhuan' , 分类名为: '玄幻魔法'
</blockquote>