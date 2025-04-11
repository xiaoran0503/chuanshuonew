<form class="layui-form layui-form-pane" method="POST" action="javascript:;">
   <!-- 伪静态设置 -->
   <fieldset class="layui-elem-field layui-field-title">
       <legend>网址路径设置</legend>
   </fieldset>

   <div class="layui-form-item">
       <label class="layui-form-label">信息页</label>
       <div class="layui-input-inline" style="width:300px;">
           <input type="text" name="fake_info_url" value="<?=$fake_info_url?>" autocomplete="off" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux"> <b style="color:#FF5722">{aid}</b> 或 <b style="color:#FF5722">{acode}</b> = 小说ID或拼音(必填) , <b style="color:#FF5722">{subaid}</b>小说子序号(可选)</div>
   </div>

   <div class="layui-form-item">
       <label class="layui-form-label">阅读页</label>
       <div class="layui-input-inline" style="width:300px;">
           <input type="text" name="fake_chapter_url" value="<?=$fake_chapter_url?>" autocomplete="off" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux"> <b style="color:#FF5722">{aid}</b> 或 <b style="color:#FF5722">{acode}</b> = 小说ID或拼音(必填) , <b style="color:#FF5722">{cid}</b>章节ID(必填), <b style="color:#FF5722">{subaid}</b>小说子序号(可选)</div>
   </div>

   <div class="layui-form-item">
       <label class="layui-form-label">独立ID</label>
       <div class="layui-input-inline" style="width:300px;">
           <input type="checkbox" name="use_orderid" lay-skin="switch" lay-text="ON|OFF" <?php if($use_orderid==1)echo ' checked' ?>>
       </div>
       <div class="layui-form-mid layui-word-aux">拼音地址模式时, 每本书的章节ID从1开始 ( 此功能会加重数据库负担 )</div>
   </div>

   <div class="layui-form-item">
       <label class="layui-form-label">分类页</label>
       <div class="layui-input-inline" style="width:300px;">
           <input type="text" name="fake_sort_url" value="<?=$fake_sort_url?>" autocomplete="off" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux"> <b style="color:#FF5722">{sortid}</b>分类ID方式(2选1) , <b style="color:#FF5722">{sortcode}</b>拼音方式(2选1) , <b style="color:#FF5722">{pid}</b>翻页码(必填)</div>
   </div>

   <div class="layui-form-item">
       <label class="layui-form-label">聚合排行页</label>
       <div class="layui-input-inline" style="width:300px;">
           <input type="text" name="fake_top" value="<?=$fake_top?>" autocomplete="off" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux">各分类的总,月,周排行等聚合在一起的页面</div>
   </div>

   <div class="layui-form-item">
       <label class="layui-form-label">最近阅读</label>
       <div class="layui-input-inline" style="width:300px;">
           <input type="text" name="fake_recentread" value="<?=$fake_recentread?>" autocomplete="off" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux">阅读记录( 足迹 )</div>
   </div>

   <fieldset class="layui-elem-field layui-field-title">
       <legend>目录页 ( 或3合1模板 )</legend>
   </fieldset>
   <div class="layui-form-item">
       <label class="layui-form-label">目录页URL</label>
       <div class="layui-input-inline" style="width:300px;">
           <input type="text" name="fake_indexlist" value="<?=$fake_indexlist?>" autocomplete="off" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux"> <b style="color:#FF5722">{aid}</b> 或 <b style="color:#FF5722">{acode}</b> = 小说ID或拼音(必填) , <b style="color:#FF5722">{pid}</b>翻页码(必填) , <b style="color:#FF5722">{subaid}</b>小说子序号(可选)</div>
   </div>

   <div class="layui-form-item">
       <label class="layui-form-label">目录分页</label>
       <div class="layui-input-inline" style="width:300px;">
           <input type="text" name="per_indexlist" value="<?=$per_indexlist?>" autocomplete="off" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux">每页显示多少章</div>
   </div>

   <blockquote class="layui-elem-quote layui-text">
       注意: <br>
       杰奇 1.x 数据库没有拼音字段, 不支持拼音网址<br>
       使用拼音路径: 高级功能 -> 小说ID转换 需关闭. 同时所有的URL设置中不能使用 {aid} 和 {subaid}<br>
   </blockquote>

</form>