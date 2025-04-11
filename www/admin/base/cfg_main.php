<form class="layui-form layui-form-pane" method="POST" action="javascript:;">

   <!-- 基本设置 -->
   <fieldset class="layui-elem-field layui-field-title">
       <legend>基本设置</legend>
   </fieldset>

   <div class="layui-form-item">
       <label class="layui-form-label">网站名称</label>
       <div class="layui-input-inline">
           <input type="text" name="sitename" value="<?=SITE_NAME?>" autocomplete="off" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux">显示在logo上的文字</div>
   </div>

   <div class="layui-form-item">
       <label class="layui-form-label">TXT网址</label>
       <div class="layui-input-inline" style="width:400px;">
           <input type="text" name="txt_url" autocomplete="off" value="<?=$txt_url?>" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux">源站的txt网址, 同服务器也可填硬盘地址</div>
   </div>

   <div class="layui-form-item">
       <label class="layui-form-label">封面网址</label>
       <div class="layui-input-inline" style="width:400px;">
           <input type="text" name="remote_img_url" autocomplete="off" value="<?=$remote_img_url?>" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux">源站的封面网址</div>
   </div>

   <div class="layui-form-item">
       <label class="layui-form-label">封面模式</label>
       <div class="layui-input-block">
           <input type="radio" name="local_img" value="0" title="使用源站封面" <?php if($local_img==0): ?> checked="" <?php endif ?>>
           <input type="radio" name="local_img" value="1" title="本地化 ( 第一次访问将从源站下载封面到本地 )" <?php if($local_img==1): ?> checked="" <?php endif ?>>
           <input type="radio" name="local_img" value="2" title="使用本地化PC封面" <?php if($local_img==2): ?> checked="" <?php endif ?>>
       </div>
   </div>

   <div class="layui-form-item">
       <label class="layui-form-label">图片章节</label>
       <div class="layui-input-inline" style="width:65px;">
           <input type="checkbox" name="is_attachment" lay-skin="switch" lay-filter="is_attachment" lay-text="有|无" <?php if($is_attachment==1)echo ' checked' ?>>
       </div>

       <div class="layui-form-mid layui-word-aux">关闭可提高运行效率</div>

       <label class="layui-form-label" style="width:120px;">图片章节网址</label>
       <div class="layui-input-inline" style="width:400px">
           <input type="text" name="att_url" autocomplete="off" placeholder="http://localhost/files/article/attachment" value="<?=$att_url?>" class="layui-input">
       </div>
   </div>

   <div class="layui-form-item">
       <label class="layui-form-label">硬盘根目录</label>
       <div class="layui-input-inline" style="width:400px;">
           <input type="text" name="root_dir" autocomplete="off" value="<?=$root_dir?>" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux">子站不设置, 源站定位到files文件夹的上级目录</div>
   </div>

   <!-- 参数设置 -->
   <fieldset class="layui-elem-field layui-field-title">
       <legend>参数设置</legend>
   </fieldset>
   <div class="layui-form-item">
       <label class="layui-form-label">模板风格</label>


       <div class="layui-input-inline">
           <select name="theme_dir">
               <!-- <option value="">请选择模板风格</option> -->
               <?php $themes_folder=__ROOT_DIR__.'/themes';
$handle=opendir($themes_folder);
while($file=readdir($handle))
{
	if($file!='.'&&$file!='..'&&is_dir($themes_folder.'/'.$file))$theme_arr[]=$file;
}
foreach($theme_arr as $k=>$v)
{
	?>
                   <option value="<?=$v?>" <?php if($theme_dir==$v)echo " selected";
	?>><?=$v?></option>
               <?php }
?>
           </select>
       </div>

       <label class="layui-form-label">3合1模板</label>
       <div class="layui-input-inline" style="width:60px;">
           <input type="checkbox" name="is_3in1" lay-skin="switch" lay-filter="is_3in1" lay-text="是|否" <?php if($is_3in1==1)echo ' checked' ?>>
       </div>
       <div class="layui-form-mid layui-word-aux">3合1模板指 ( 信息页+目录页+翻页 ) 合并成一个页面</div>
   </div>

   <div class="layui-form-item">
       <label class="layui-form-label">封推 ID</label>
       <div class="layui-input-inline" style="width:450px;">
           <input type="text" name="commend_ids" value="<?=$commend_ids?>" autocomplete="off" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux">首页封推小说 ( 数据库ID,逗号隔开,一般模板几本填几本 )</div>
   </div>

   <div class="layui-form-item">
       <label class="layui-form-label">列表页数量</label>
       <div class="layui-input-inline">
           <input type="text" name="category_per_page" value="<?=$category_per_page?>" autocomplete="off" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux">分类/标签列表页: 每页显示多少本</div>
   </div>

   <div class="layui-form-item">
       <label class="layui-form-label">阅读页分页</label>
       <div class="layui-input-inline">
           <input type="text" name="readpage_split_lines" value="<?=$readpage_split_lines?>" autocomplete="off" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux">阅读页的内容如果超过n行,则平均分为2页</div>
   </div>

   <div class="layui-form-item">
       <label class="layui-form-label">推荐票数</label>
       <div class="layui-input-inline">
           <input type="text" name="vote_perday" value="<?=$vote_perday?:3?>" autocomplete="off" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux">注册用户每天可推荐小说的次数</div>
   </div>

   <div class="layui-form-item">
       <label class="layui-form-label">统计点击</label>
       <div class="layui-input-inline">
           <input type="checkbox" name="count_visit" lay-skin="switch" lay-text="ON|OFF" <?php if($count_visit==1)echo ' checked' ?>>
       </div>
       <div class="layui-form-mid layui-word-aux">统计小说的点击量 , 关闭可提高数据库性能</div>
   </div>

</form>