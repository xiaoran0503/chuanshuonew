<?php require_once '../header.php';
require_once $config_file=__ROOT_DIR__.'/shipsay/json/config.ini.php';
$tmpvar=file_get_contents($config_file);
preg_match('#//轮播图([\s\S]+)//轮播图结束#is',$tmpvar,$matches);
$swipers=str_replace(' ','',$matches[1]);
?>
<div class="layui-body">
   <!-- 内容主体区域 -->
   <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief" style="margin-top: 6px;">
       <ul class="layui-tab-title">
           <li class="layui-this">APP 运营设置</li>
           <li>API 接口设置</li>
       </ul>
       <div class="layui-tab-content" style="height: 100px;">
           <div class="layui-tab-item layui-show"><?php require_once 'app_base.php' ?></div>
           <div class="layui-tab-item"><?php require_once 'app_api.php' ?></div>
       </div>
   </div>
   <!-- /内容主体区域 -->
</div>

<div class="layui-footer">
   <button class="layui-btn save-btn-filter">保存设置</button>
   <span class="layui-layout-right layui-word-aux" style="margin-right: 10px;">&copy; 船说CMS</span>
</div>

</div> <!-- /header -->

<script>
   $('.save-btn-filter').on('click', function() {
       $.ajax({
           type: "POST",
           url: "/<?=$admin_url?>/savecfgs.php?do=app",
           data: {
               "commend_ids": $("input[name='commend_ids']").val(),
               "qrcode": $("input[name='qrcode']").val(),
               "download_url": $("input[name='download_url']").val(),
               "swipers": $(".swipers").val(),
               "adsense": $(".adsense").val() || '',
               "key": $("input[name='key']").val(),
               "auth_mode": $("input[name='auth_mode']:checked").val(),
               "json_cache_time": $("input[name='json_cache_time']").val() || 0,
               "uni_app_id": $("input[name='uni_app_id']").val() || '',
               "current_ver": $("input[name='current_ver']").val(),
               "update_note": $("input[name='update_note']").val() || '',
               "download_url": $("input[name='download_url']").val() || '',

               "config_file": "<?=$config_file?>"
           },
           async: true,
           success: function(state) {
               layer.msg(state == 200 ? '保存成功' : '保存失败,请检查配置(App)');
           }
       })
   })

   layui.use(['element', 'form'], function() {
       let element = layui.element;
       let form = layui.form;
       form.render();
   })
</script>

</body>

</html>