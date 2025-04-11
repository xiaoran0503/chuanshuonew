<?php require_once '../header.php';
include_once $config_file=__ROOT_DIR__.'/shipsay/configs/count.ini.php';
?>
<!-- 内容主体区域 -->
   <div class="layui-body">
       <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief" style="margin-top: 6px;">
           <ul class="layui-tab-title">
               <li class="layui-this">统计代码①</li>
               <li>统计代码②</li>
               <li>统计代码③</li>
           </ul>
           <div class="layui-tab-content" style="height: 100px;">
               <div class="layui-tab-item layui-show"><?php include_once 'cfg_count_1.php' ?></div>
               <div class="layui-tab-item"><?php include_once 'cfg_count_2.php' ?></div>
               <div class="layui-tab-item"><?php include_once 'cfg_count_3.php' ?></div>
           </div>
       </div>
   </div>
<!-- /内容主体区域 -->

 <div class="layui-footer">
   <button class="layui-btn save-btn-link">保存设置</button>
   <span class="layui-layout-right layui-word-aux" style="margin-right: 10px;">&copy; 船说CMS</span>
 </div>
 
</div> <!-- /header -->

<script>
   $('.save-btn-link').on('click',function(){
       count1_enable = $("input[name='count1_enable']").is(':checked') ? 1 : 0;
       count2_enable = $("input[name='count2_enable']").is(':checked') ? 1 : 0;
       count3_enable = $("input[name='count3_enable']").is(':checked') ? 1 : 0;
       $.ajax({
           type: "POST",
           url: "/<?=$admin_url?>/savecfgs.php?do=count",
           data: {
               "count1_enable" : count1_enable,
               "count2_enable" : count2_enable,
               "count3_enable" : count3_enable,
               "count1_html" : $("#count1_html").val(),
               "count2_html" : $("#count2_html").val(),
               "count3_html" : $("#count3_html").val(),
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