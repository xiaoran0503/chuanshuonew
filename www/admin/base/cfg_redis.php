<form class="layui-form layui-form-pane" method="POST" action="javascript:;">

   <fieldset class="layui-elem-field layui-field-title">
       <legend>Redis 缓存参数设置</legend>
   </fieldset>


   <div class="layui-form-item">
       <label class="layui-form-label">缓存开关</label>
       <div class="layui-input-inline" style="width:86px;">
           <input type="checkbox" name="use_redis" lay-filter="switchTest" lay-skin="switch" lay-text="ON|OFF" <?php if($use_redis==1)echo ' checked' ?>>
       </div>
       <button class="layui-btn layui-btn-warm" id="flushdb" onclick="javascript:flushDb();" style="float:left;">清空缓存</button>
       <div class="layui-form-mid layui-word-aux" style="display:inline-block;margin-left:12px;">注意: 要使用Redis缓存, <b style="color:#FF5722">数据库IP地址</b>必须填外网地址</span></div>
   </div>

   <div class="layui-form-item">
       <label class="layui-form-label">缓存服务器</label>
       <div class="layui-input-inline">
           <input type="text" name="redishost" value="<?=$redisarr['host']?>" autocomplete="off" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux">默认: 127.0.0.1</span></div>
   </div>

   <div class="layui-form-item">
       <label class="layui-form-label">端口</label>
       <div class="layui-input-inline">
           <input type="text" name="redisport" value="<?=$redisarr['port']?>" autocomplete="off" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux">默认: 6379</span></div>
   </div>

   <div class="layui-form-item">
       <label class="layui-form-label">密码</label>
       <div class="layui-input-inline">
           <input type="text" name="redispass" value="<?=$redisarr['pass']?>" autocomplete="off" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux">默认: 空</span></div>
   </div>

   <div class="layui-form-item">
       <label class="layui-form-label">缓存库</label>
       <div class="layui-input-inline">
           <input type="text" name="redisdb" placeholder="无" value="<?=$redisarr['db']?>" autocomplete="off" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux">( 0-15, 默认0 ) 不清楚请百度或使用默认设置</span></div>
   </div>

   <fieldset class="layui-elem-field layui-field-title">
       <legend>Redis 缓存时间设置</legend>
   </fieldset>

   <div class="layui-form-item">
       <label class="layui-form-label">首页</label>
       <div class="layui-input-inline">
           <input type="text" name="home_cache_time" value="<?=$home_cache_time?>" autocomplete="off" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux">首页缓存时间 ( 秒 )</span></div>
   </div>

   <div class="layui-form-item">
       <label class="layui-form-label">信息页</label>
       <div class="layui-input-inline">
           <input type="text" name="info_cache_time" value="<?=$info_cache_time?>" autocomplete="off" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux">信息页缓存时间 ( 秒 )</span></div>
   </div>

   <div class="layui-form-item">
       <label class="layui-form-label">分类页</label>
       <div class="layui-input-inline">
           <input type="text" name="category_cache_time" value="<?=$category_cache_time?>" autocomplete="off" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux">分类列表页缓存时间 ( 秒 )</span></div>
   </div>

   <div class="layui-form-item">
       <label class="layui-form-label">其他页面</label>
       <div class="layui-input-inline">
           <input type="text" name="cache_time" value="<?=$cache_time?>" autocomplete="off" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux">其他页面 ( 秒 )</span></div>
   </div>

</form>

<script>
   if ($('input[name="use_redis"]').is(':checked')) {
       $('#flushdb').removeClass('layui-btn-disabled').removeAttr('disabled');
   } else {
       $('#flushdb').addClass('layui-btn-disabled').attr('disabled', true);
   }


   layui.use('form', function() {
       form = layui.form;
       form.on('switch(switchTest)', function(data) {
           if (this.checked) {
               $('#flushdb').removeClass('layui-btn-disabled').removeAttr('disabled')
           } else {
               $('#flushdb').addClass('layui-btn-disabled').attr('disabled', true)
           }
       });
   });

   function flushDb() {
       layer.confirm('确定要清空全部缓存数据吗?', function() {
           $.ajax({
               type: 'POST',
               url: '../include/flushDb.php',
               data: {
                   'flushDb': 'flushDb'
               },
               success: function(state) {
                   layer.msg(state = '200' ? '缓存清除完毕' : '清除失败,请检查配置');
               }
           })
       })
   }
</script>