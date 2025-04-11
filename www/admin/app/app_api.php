<form class="layui-form layui-form-pane" method="POST" action="javascript:;">

   <fieldset class="layui-elem-field layui-field-title">
       <legend>API 接口</legend>
   </fieldset>

   <div class="layui-form-item">
       <label class="layui-form-label">您的秘钥</label>
       <div class="layui-input-inline">
           <input type="text" name="key" value="<?=$key?>" autocomplete="off" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux">修改秘钥 , APP需重新打包</span></div>
   </div>

   <div class="layui-form-item">
       <label class="layui-form-label">接口验证</label>
       <div class="layui-input-block">
           <input type="radio" name="auth_mode" value="0" title="不验证" <?php if($auth_mode==0): ?> checked="" <?php endif ?>>
           <input type="radio" name="auth_mode" value="1" title="只验证阅读页 ( 建议 )" <?php if($auth_mode==1): ?> checked="" <?php endif ?>>
           <input type="radio" name="auth_mode" value="2" title="对所有页面验证" <?php if($auth_mode==2): ?> checked="" <?php endif ?>>
       </div>
   </div>

   <div class="layui-form-item">
       <label class="layui-form-label">缓存时间</label>
       <div class="layui-input-inline">
           <input type="text" name="json_cache_time" value="<?=$json_cache_time?>" autocomplete="off" class="layui-input">
       </div>
       <div class="layui-form-mid layui-word-aux">( 秒 ) 使用缓存必须先在 '参数设置' -> 'Redis缓存' 中启用缓存</span></div>
   </div>
</form>