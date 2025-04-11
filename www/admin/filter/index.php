<?php require_once '../header.php';
require_once $config_file=__ROOT_DIR__.'/shipsay/configs/filter.ini.php';
?>
<!-- 内容主体区域 -->
 <div class="layui-body">

   <form class="layui-form layui-form-pane" method="POST" action="javascript:;">
       <fieldset class="layui-elem-field layui-field-title"><legend>敏感词过滤替换</legend></fieldset>

       <div class="layui-form-item">
           <label class="layui-form-label">开关</label>
           <div class="layui-input-inline"  style="width:100px;">
               <input type="checkbox" name="is_filter" lay-skin="switch" lay-filter="is_filter" lay-text="ON|OFF"<?php if($ShipSayFilter['is_filter']==1)echo ' checked' ?>>
           </div>

           <div class="layui-form-mid layui-word-aux">关闭可提高运行效率</div>

       </div>

       <div class="layui-form-item layui-form-text">
           <label class="layui-form-label">格式: <span class="layui-bg-orange">需替换的内容♂替换结果</span> ( ♂ 符号必填, 替换结果可不填 )；一行一个,通配符 <span class="layui-bg-orange">$$$$</span> 可代表任意字符</label>
           <div class="layui-input-block">
               <textarea id="filter" class="layui-textarea" style="min-height:500px"><?=trim($ShipSayFilter['filter_ini'])?></textarea>
           </div>
       </div>

   </form>
 </div>
<!-- /内容主体区域 -->

 <div class="layui-footer">
   <button class="layui-btn save-btn-filter">保存设置</button>
   <span class="layui-layout-right layui-word-aux" style="margin-right: 10px;">&copy; 船说CMS</span>
 </div>
 
</div> <!-- /header -->

<script>
   $('.save-btn-filter').on('click',function(){
       is_filter = $("input[name='is_filter']").is(':checked') ? 1 : 0;
       $.ajax({
           type: "POST",
           url: "/<?=$admin_url?>/savecfgs.php?do=filter",
           data: {
               "is_filter" : is_filter,
               "filter_ini" : $("#filter").val(),
               "config_file" : "<?=$config_file?>"
           },
           async: true,
           success: function(state){
               layer.msg(state == 200 ? '保存成功' : '保存失败,请检查配置(filter)');
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