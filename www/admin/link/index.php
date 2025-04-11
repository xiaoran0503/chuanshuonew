<?php require_once '../header.php';
require_once $config_file=__ROOT_DIR__.'/shipsay/configs/link.ini.php';
?>
<!-- 内容主体区域 -->
 <div class="layui-body">

   <form class="layui-form layui-form-pane" method="POST" action="javascript:;">
       <fieldset class="layui-elem-field layui-field-title"><legend>友情链接管理</legend></fieldset>

       <div class="layui-form-item">
           <label class="layui-form-label">显示开关</label>
           <div class="layui-input-inline"  style="width:100px;">
               <input type="checkbox" name="is_link" lay-skin="switch" lay-filter="is_link" lay-text="ON|OFF"<?php if($ShipSayLink['is_link']==1)echo ' checked';
?>>
           </div>

           <div class="layui-form-mid layui-word-aux">网站是否显示友情链接</div>

       </div>

       <div class="layui-form-item layui-form-text">
           
       </div>

       <div class="layui-form-item layui-form-text">
           <label class="layui-form-label">格式: 一行一条</label>
           <div class="layui-input-block">
               <textarea id="link" class="layui-textarea" style="min-height:500px"><?=trim($ShipSayLink['link_ini'])?></textarea>
           </div>
       </div>

   </form>
 </div>
<!-- /内容主体区域 -->

 <div class="layui-footer">
   <button class="layui-btn save-btn-link">保存设置</button>
   <span class="layui-layout-right layui-word-aux" style="margin-right: 10px;">&copy; 船说CMS</span>
 </div>
 
</div> <!-- /header -->

<script>
   $('.save-btn-link').on('click',function(){
       is_link = $("input[name='is_link']").is(':checked') ? 1 : 0;
       $.ajax({
           type: "POST",
           url: "/<?=$admin_url?>/savecfgs.php?do=link",
           data: {
               "is_link" : is_link,
               "link_ini" : $("#link").val(),
               "config_file" : "<?=$config_file?>"
           },
           async: true,
           success: function(state){
               layer.msg(state == 200 ? '保存成功' : '保存失败,请检查配置');
           }
       })
   })

   layui.use(['element','form'], function(){
     let element = layui.element;
     let form = layui.form;
     form.render();
   })

</script>

</body>
</html>